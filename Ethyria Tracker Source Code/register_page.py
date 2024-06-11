import tkinter as tk
import sqlite3
from tkinter import messagebox
from tkinter.ttk import *
from database import Database


class RegisterPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Username:").place(x=220, y=100)
        self.username_entry = Entry(self)
        self.username_entry.place(x=300, y=100)

        Label(self, text="Password:").place(x=220, y=160)
        self.password_entry = Entry(self, show="*")
        self.password_entry.place(x=300, y=160)

        Label(self, text="Confirm Password:").place(x=180, y=220)
        self.confirm_password_entry = Entry(self, show="*")
        self.confirm_password_entry.place(x=300, y=220)

        Button(self, text="Register", command=self.register_user).place(x=300, y=280)
        Button(self, text="Back", command=lambda: controller.show_frame("LoginPage")).place(x=400,y=280)

    def register_user(self):
        username = self.username_entry.get().strip()
        password1 = self.password_entry.get().strip()
        password2 = self.confirm_password_entry.get().strip()

        if not username or not password1 or not password2:
            messagebox.showwarning("Input Error", "Username and password fields cannot be empty.")
            return

        if password1 == password2:
            if self.username_exists(username):
                messagebox.showerror("Error", "This username is already registered.")
                self.clear_entries()
                return

            try:
                self.db.execute_query("INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)", (username, password1, 'user'))
                self.controller.frames["ViewAccountsPage"].load_accounts()
                self.clear_entries()
                messagebox.showinfo("Success", "Registration successful!")
                self.controller.show_frame("LoginPage")
            except sqlite3.IntegrityError:
                messagebox.showerror("Error", "An error occurred while registering. Please try again.")
                self.clear_entries()
        else:
            messagebox.showerror("Error", "Passwords do not match.")
            self.clear_entries()
            
    def username_exists(self, username):
        result = self.db.fetch_one("SELECT 1 FROM users WHERE username = ?", (username,))
        return result is not None
    
    def clear_entries(self):
        self.username_entry.delete(0, 'end')
        self.password_entry.delete(0, 'end')
        self.confirm_password_entry.delete(0, 'end')
