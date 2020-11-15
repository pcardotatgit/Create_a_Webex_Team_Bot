'''
    Update the targetURL of a Webex Team Bot
'''
import requests

targetURL="http://{host}/bot_path/bot_logic.php"
webhookId = 'Y2lzY29zcGFyazovL3VzL1dFQ   xxxxxxxxxxxxxxxxxxxxx 2Q5LWJhOGQtNmNmMzk3NGJjN2'
BOT_ACCESS_TOKEN = 'ODZkZmU5ZTEtMWU XXXXXXXXXXXXXXXXXXXXXXXXXXX _PF84_1eb65fdf-9643-417f-9974-ad72cae0e1'

URL = f'https://webexapis.com/v1/webhooks/{webhookId}'
headers = {'Authorization': 'Bearer ' + BOT_ACCESS_TOKEN,
           'Content-type': 'application/json;charset=utf-8'}
post_data = {'targetUrl': targetURL}
response = requests.put(URL, json=post_data, headers=headers)
if response.status_code == 200:
    # Great your message was posted!
    #message_id = response.json['id']
    #message_text = response.json['text']
    print("WebHook succesfuly updated with a new targetURL")
    #print(message_text)
    print("====================")
    print(response)
else:
    # Oops something went wrong...  Better do something about it.
    print(response.status_code, response.text)