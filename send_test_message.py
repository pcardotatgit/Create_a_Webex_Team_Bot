'''
    send a message to a webex team room
'''
import requests
import sys

lines=[]
with open ('init_key_and_bot_id.txt','r') as file:
    content = file.read()
    lines=content.split('\n')
    DESTINATION_ROOM_ID=(lines[0].split(':'))[1]
    BOT_ACCESS_TOKEN=(lines[1].split(':'))[1]
print(DESTINATION_ROOM_ID)
print(BOT_ACCESS_TOKEN)

URL = 'https://api.ciscospark.com/v1/messages'

MESSAGE_TEXT = 'Hello ! ( test from my python script )'

headers = {'Authorization': 'Bearer ' + BOT_ACCESS_TOKEN,
           'Content-type': 'application/json;charset=utf-8'}
post_data = {'roomId': DESTINATION_ROOM_ID,
             'text': MESSAGE_TEXT}
response = requests.post(URL, json=post_data, headers=headers)
if response.status_code == 200:
    # Great your message was posted!
    #message_id = response.json['id']
    #message_text = response.json['text']
    print("New message created")
    #print(message_text)
    print("====================")
    print(response)
else:
    # Oops something went wrong...  Better do something about it.
    print(response.status_code, response.text)