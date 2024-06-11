import tkinter as tk
from tkinter.ttk import *
from tkinter import messagebox
from database import Database

class ViewAccountsPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="View Accounts", font=("Helvetica", 16)).pack(pady=10)

        columns = ("ID", "Username", "Password", "Account Type")
        self.tree = Treeview(self, columns=columns, show="headings")
        self.tree.heading("ID", text="ID")
        self.tree.heading("Username", text="Username")
        self.tree.heading("Password", text="Password")  # New password column
        self.tree.heading("Account Type", text="Account Type")
        self.tree.pack(fill=tk.BOTH, expand=True, padx=20, pady=20)

        Button(self, text="Back", command=lambda: controller.show_frame("AdminPage")).pack(pady=10)

        self.load_accounts()

    def load_accounts(self):
        for item in self.tree.get_children():
            self.tree.delete(item)
        
        rows = self.db.fetch_query("SELECT id, username, password, account_type FROM users")
        for row in rows:
            self.tree.insert('', tk.END, values=row)