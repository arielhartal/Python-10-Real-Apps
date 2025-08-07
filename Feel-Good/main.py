from kivy.app import App
from kivy.lang import Builder
from kivy.uix.screenmanager import ScreenManager, Screen
import json, glob
from datetime import datetime
from pathlib import Path
import random
from hoverable import HoverBehavior
from kivy.uix.image import Image
from kivy.uix.behaviors import ButtonBehavior

Builder.load_file('design.kv')


class LoginScreen(Screen):
    def sign_up(self):
        self.manager.transition.direction = 'right'
        self.manager.current = "sign_up_screen"
        
    def login(self, uname, pword):
        with open("users.json") as file:
            users = json.load(file)
        if uname in users and users[uname]['password'] == pword:
            self.manager.transition.direction = "left"
            self.manager.current = 'login_screen_success'
        else:
            self.ids.login_wrong.text = "Wrong username or password!"
    
    def forgot_pass(self):
        self.manager.transition.direction = 'right'
        self.manager.current = "forgot_pass_screen"


class RootWidget(ScreenManager):
    pass


class SignUpScreen(Screen):
    def add_user(self, uname, pword):
        with open("users.json") as file:
            users = json.load(file)
        
        if uname == '' or pword == '' or uname in users:
            self.ids.if_sign_up.text = "Failed, Try again"
        else:
            users[uname] = {'username': uname, 'password': pword,
                'created': datetime.now().strftime("%Y-%m-%d %H-%M-%S")}
            
            with open("users.json", 'w') as file:
                json.dump(users, file)
            self.manager.transition.direction = 'left'
            self.manager.current = "sign_up_screen_success"
    
    def go_back(self):
        self.manager.transition.direction = "right"
        self.manager.current = "login_screen"
            

class SignUpScreenSuccess(Screen):
    def login_screen(self):

        self.manager.transition.direction = 'right'
        self.manager.current = "login_screen"


class LoginScreenSuccess(Screen):
    def log_out(self):
        self.manager.transition.direction = "right"
        self.manager.current = "login_screen"

    def get_quote(self, feel):
        feel = feel.lower()
        available_feelings = glob.glob("quotes/*txt")


        available_feelings = [Path(filename).stem for filename in
                                available_feelings]

        if feel in available_feelings:
            with open(f"quotes/{feel}.txt", encoding="utf-8") as file:
                quotes = file.readlines()
            self.ids.quote.text = random.choice(quotes)
        else:
            self.ids.quote.text = "Try another feeling"
        
class ForgotPassScreen(Screen):
    def confirm_new(self, uname, npass, cnpass):
        with open("users.json") as file:
                users = json.load(file)
        
        if npass != cnpass or uname not in users or uname == '' or npass == '' or cnpass == '':
            self.ids.labelevent.text = "Something went wrong..."

        else:
            self.ids.labelevent.text = "Password changed successfuly"
            users[uname]['password'] = npass

            with open("users.json", 'w') as file:
                json.dump(users, file)

   
    def go_back(self):
        self.manager.transition.direction = "left"
        self.manager.current = "login_screen"
      

class ImageButton(ButtonBehavior, HoverBehavior, Image):
    pass


class MainApp(App):
    def build(self):
        return RootWidget()

if __name__ == "__main__":
    MainApp().run()
