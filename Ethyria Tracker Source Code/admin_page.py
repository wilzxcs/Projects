import tkinter as tk
from tkinter.ttk import *
from tkinter import messagebox
from database import Database

class AdminPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Ethyrial Financial Tracker Admin Page", font=("Helvetica", 16)).pack(pady=10)

        Button(self, text="Add Account", command=lambda: controller.show_frame("AddAccountPage")).pack(pady=5)
        Button(self, text="View Accounts", command=lambda: controller.show_frame("ViewAccountsPage")).pack(pady=5)
        Button(self, text="Delete Account", command=lambda: controller.show_frame("DeleteAccountPage")).pack(pady=5)
        Button(self, text="Profile", command=lambda: controller.show_frame("ProfilePage")).pack(pady=5)
