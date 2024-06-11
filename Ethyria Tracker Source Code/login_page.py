import tkinter as tk
from tkinter import messagebox
from tkinter.ttk import *
from database import Database

class LoginPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Username").place(x=220, y=140)
        self.username_entry = Entry(self)
        self.username_entry.place(x=300, y=140)

        Label(self, text="Password").place(x=220, y=200)
        self.password_entry = Entry(self, show="*")
        self.password_entry.place(x=300, y=200)

        Button(self, text="Login", command=self.on_login).place(x=240, y=250)
        Button(self, text="Register", command=lambda: controller.show_frame("RegisterPage")).place(x=320, y=250)

    def on_login(self):
        username = self.username_entry.get().strip()
        password = self.password_entry.get().strip()

        if not username or not password:
            messagebox.showwarning("Input Error", "Username and password fields cannot be empty.")
            return

        user = self.db.fetch_one("SELECT * FROM users WHERE username = ? AND password = ?", (username, password))

        try:
            user = self.db.fetch_one("SELECT * FROM users WHERE username = ?", (username,))
            if not user:
                raise ValueError("Username not found")
            user_id, _, _, account_type = user
            if password != user[2]:
                raise ValueError("Invalid password")
            self.controller.current_user_id = user_id
            if account_type == 'admin':
                self.controller.show_frame("AdminPage")
            else:
                self.controller.show_frame("MainPage")
            self.clear_entries()
        except Exception as e:
            messagebox.showerror("Login Error", str(e))
            self.clear_entries()

    def clear_entries(self):
        self.username_entry.delete(0, 'end')
        self.password_entry.delete(0, 'end')
