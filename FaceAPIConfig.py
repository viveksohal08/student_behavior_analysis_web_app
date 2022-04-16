#!C:\Users\vivek\AppData\Local\Programs\Python\Python36\python.exe
print("Content-Type: text/html\n")
subscription_key = "2f78bfbf43db4fe59641d8a0eb9eb13d" #DSFace API
face_api_url = 'https://westus.api.cognitive.microsoft.com/face/v1.0/detect'

def config():
    print("Call Config")
    return subscription_key, face_api_url