#!C:\Users\vivek\AppData\Local\Programs\Python\Python39\python.exe
print("Content-Type: text/html\n")
subscription_key = "51340f570bf144899da2951f2d1bd7dd"
face_api_url = 'https://student-behavior-analysis-instance.cognitiveservices.azure.com/'

def config():
    print("Call Config")
    return subscription_key, face_api_url