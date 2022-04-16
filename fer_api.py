from __future__ import division
#!C:\Users\vivek\AppData\Local\Programs\Python\Python39\python.exe
print("Content-Type: text/html\n")
import cv2
import numpy as np
import os
import requests
import matplotlib.pyplot as plt
from PIL import Image
from matplotlib import patches
from io import BytesIO
import FaceAPIConfig as cnfg
import mysql.connector
import sys
from scipy.spatial import distance as dist
import numpy as np
import time
import dlib
from collections import OrderedDict
#from scipy import ndimage

#Reading values from upload.php script via Command Line Argument
video_name = sys.argv[1]
class_name = sys.argv[2]
lecture_name = sys.argv[3]
date = sys.argv[4]
time = sys.argv[5]

time2 = time.replace(":","-")

EYE_AR_THRESH = 0.18

FACIAL_LANDMARKS_IDXS = OrderedDict([
    ("mouth", (48, 68)),
    ("right_eyebrow", (17, 22)),
    ("left_eyebrow", (22, 27)),
    ("right_eye", (36, 42)),
    ("left_eye", (42, 48)),
    ("nose", (27, 35)),
    ("jaw", (0, 17))
])

def eye_aspect_ratio(eye):
    A = dist.euclidean(eye[1], eye[5])
    B = dist.euclidean(eye[2], eye[4])
    C = dist.euclidean(eye[0], eye[3])
    ear = (A + B) / (2.0 * C)
    return ear

def resize(img, width=None, height=None, interpolation=cv2.INTER_AREA):
    global ratio
    w, h, _ = img.shape
    if width is None and height is None:
        return img
    elif width is None:
        ratio = height / h
        width = int(w * ratio)
        resized = cv2.resize(img, (height, width), interpolation)
        return resized
    else:
        ratio = width / w
        height = int(h * ratio)
        resized = cv2.resize(img, (height, width), interpolation)
        return resized

def shape_to_np(shape, dtype="int"):
    coords = np.zeros((68, 2), dtype=dtype)
    for i in range(0, 68):
        coords[i] = (shape.part(i).x, shape.part(i).y)
    return coords

#Create connection
mydb = mysql.connector.connect(
	host="localhost",
  	user="root",
  	passwd="",
  	database="sba"
)

def assure_path_exists(path):
    dir = os.path.dirname(path)
    if not os.path.exists(dir):
        os.makedirs(dir)

"""def largest(arr,n):
	max = arr[0]
	for i in range(1,n):
		if arr[i] > max:
			max = arr[i]
			ind = i
	return max,ind"""

def facecrop(image,x,y,w,h,counter):
    img = cv2.imread(image)
    cv2.rectangle(img, (x,y), (x+w,y+h), (255,255,255))
    sub_face = img[y:y+h, x:x+w]
    fname, ext = os.path.splitext(image)
    face_name = fname + "_face" + str(counter) + ext
    cv2.imwrite(face_name, sub_face)
    gray = cv2.cvtColor(sub_face,cv2.COLOR_BGR2GRAY)
    ID, confidence = recognizer.predict(gray)
    return face_name, ID, gray
    #return face_name
    
def facedetect_and_getemotion(image_name, frame, currentFrame):
	#image_path = os.path.join(image_name)
	image_data = open(image_name, "rb")
	subscription_key, face_api_url = cnfg.config();
	headers = {'Content-Type': 'application/octet-stream',
           'Ocp-Apim-Subscription-Key': subscription_key}
	params = {
	    'returnFaceAttributes': 'emotion'
	}
	try:
		response = requests.post(face_api_url, params=params, headers=headers, data=image_data)
		response.raise_for_status()
		faces = response.json()
		counter = 0
		for face in faces:
		    fr = face["faceRectangle"]
		    fa = face["faceAttributes"]
		    fe = fa["emotion"]
		    #anger = fe["anger"]
		    contempt = fe["contempt"]
		    disgust = fe["disgust"]
		    #fear = fe["fear"]
		    happiness = fe["happiness"]
		    neutral = fe["neutral"]
		    sadness = fe["sadness"]
		    #surprise = fe["surprise"]
		    face_name, roll_no, cropped_face = facecrop(image_name, fr["left"], fr["top"], fr["width"], fr["height"], counter)
		    #face_name = facecrop(image_name, fr["left"], fr["top"], fr["width"], fr["height"],counter)
		    counter += 1
		    rects = detector(cropped_face, 0)
		    emotion = ""
		    for rect in rects:
		    	shape = predictor(cropped_face, rect)
		    	shape = shape_to_np(shape)
		    	leftEye = shape[lStart:lEnd]
		    	rightEye = shape[rStart:rEnd]
		    	leftEAR = eye_aspect_ratio(leftEye)
		    	rightEAR = eye_aspect_ratio(rightEye)
		    	ear = min([leftEAR,rightEAR])
		    	if ear < EYE_AR_THRESH:
		    		emotion = "Sleepy"		
		    """arr = [anger,contempt,disgust,fear,happiness,neutral,sadness,surprise]
		    n = len(arr)
		    ans, pos = largest(arr,n)
		    if(pos == 0):
		    	emotion = "Angry"
		    elif(pos == 1):
		    	emotion = "Contempt"
		    elif(pos == 2):
		    	emotion = "Disgust"
		    elif(pos == 3):
		    	emotion = "Fear"
		    elif(pos == 4):
		    	emotion = "Happy"
		    elif(pos == 5):
		    	emotion = "Neutral"
		    elif(pos == 6):
		    	emotion = "Sad"
		    elif(pos == 7):
		    	emotion = "Surprise"
		    else:
		    	emotion = "Not detected"	"""
		    # Can be improved
		    print(currentFrame)
		    if emotion != "Sleepy": 
			    if contempt >= 0.1 or disgust >= 0.1 or sadness >= 0.1:
			    	emotion = "Bored"
			    elif neutral > happiness + 0.1:
			    	emotion = "Attentive"
			    else:
			    	emotion = "Joyful"

		    insert_data(currentFrame, image_name, face_name , roll_no, emotion, class_name, lecture_name, date, time)
	except Exception as e:
		print("Frame skipped.", e)

def insert_data(frame_count, frame_image, face_image, face_id, emotion, class_name, lecture_name, date, time):
	mycursor = mydb.cursor()
	#Insert into database "sba".
	sql = "INSERT INTO image_info (frame_number, frame_image, face_image, face_id, expression, class_name, lecture_name, date, time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
	val = (frame_count, frame_image, face_image, face_id, emotion, class_name, lecture_name, date, time)
	mycursor.execute(sql, val)
	mydb.commit()

predictor_path = 'C:/xampp/htdocs/sba/shape_predictor_68_face_landmarks.dat'
detector = dlib.get_frontal_face_detector()
predictor = dlib.shape_predictor(predictor_path)
(lStart, lEnd) = FACIAL_LANDMARKS_IDXS["left_eye"]
(rStart, rEnd) = FACIAL_LANDMARKS_IDXS["right_eye"]
fileStream = True

recognizer = cv2.face.LBPHFaceRecognizer_create()
assure_path_exists("trainer/")
recognizer.read('trainer/trainer.yml')

cap = cv2.VideoCapture(video_name)
try:
    os.makedirs('data/' + class_name + '/' + lecture_name + '/' + date + ' ' + time2+'/')
except OSError:
    print('Error: Creating directory of data')
currentFrame = 0
while(True):
    #Capture frame after every 1 sec of video
    cap.set(cv2.CAP_PROP_POS_MSEC, currentFrame * 1000)
    ret, frame = cap.read()
    if not ret:
        break
    name = './data/' + class_name + '/' + lecture_name + '/' + date + ' ' + time2 + '/frame' + str(int(currentFrame)) + '.jpg'
    cv2.imwrite(name, frame)
    facedetect_and_getemotion(name, frame, currentFrame)
    currentFrame += 1
cap.release()
cv2.destroyAllWindows()

"""
Reading values from upload.php script via Command Line Argument
Capture frame from video uploaded after every 1 sec
Get face rectangles of frame captured from Microsoft Azure Cognitive Service
For first face rectangle, face is cropped along with its recognition and emotion
Details are entered in the database
"""