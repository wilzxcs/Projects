import tkinter as tk
from tkinter.ttk import *
from tkinter import messagebox
from tkinter import *
from database import Database

class AddAccountPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Add Account", font=("Helvetica", 16)).pack(pady=10)
        
        Label(self, text="Username").pack(pady=5)
        self.username_entry = Entry(self)
        self.username_entry.pack(pady=5)

        Label(self, text="Password").pack(pady=5)
        self.password_entry = Entry(self, show="*")
        self.password_entry.pack(pady=5)

        Label(self, text="Account Type").pack(pady=5)
        self.account_type = StringVar(value="user")
        Radiobutton(self, text="Admin", variable=self.account_type, value="admin").pack()
        Radiobutton(self, text="User", variable=self.account_type, value="user").pack()

        Button(self, text="Add", command=self.add_account).pack(pady=20)
        Button(self, text="Back", command=lambda: self.back_to_admin_page()).pack(pady=10)

    def add_account(self):
        username = self.username_entry.get().strip()
        password = self.password_entry.get().strip()
        account_type = self.account_type.get()

        if not username or not password:
            messagebox.showwarning("Input Error", "All fields must be filled.")
            self.clear_entries()
            return

        try:
        # Check if the username already exists
            if self.username_exists(username):
                messagebox.showerror("Error", "This username already exists.")
                self.clear_entries()
                return

        # If username does not exist, add the account
            self.db.execute_query("INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)",
                                  (username, password, account_type))
            messagebox.showinfo("Success", "Account added successfully!")
            self.clear_entries()
            self.controller.frames["ViewAccountsPage"].load_accounts()
        except Exception as e:
            messagebox.showerror("Error", f"An error occurred: {str(e)}")
            
    def username_exists(self, username):
        # Check if the username exists in the database
        result = self.db.fetch_one("SELECT 1 FROM users WHERE username = ?", (username,))
        self.clear_entries()
        return result is not None
    
    def back_to_admin_page(self):
        self.clear_entries()
        self.controller.show_frame("AdminPage")
        
    def clear_entries(self):
        self.username_entry.delete(0, 'end')
        self.password_entry.delete(0, 'end')
        self.account_type.set("user")

