import tkinter as tk
from tkinter.ttk import *

class MainPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        Label(self, text="Ethyrial Financial Tracker", font=("Helvetica", 16)).pack(pady=20)

        Button(self, text="Add Transaction", command=lambda: controller.show_frame("AddTransactionPage")).pack(pady=5)
        Button(self, text="Budget", command=lambda: controller.show_frame("SetBudgetPage")).pack(pady=5)
        Button(self, text="View Transactions", command=lambda: controller.show_frame("ViewTransactionsPage")).pack(pady=5)
        Button(self, text="Profile", command=lambda: controller.show_frame("ProfilePage")).pack(pady=20)

    def add_transaction(self):
        self.controller.show_frame("AddTransactionPage")
        pass

    def set_budget(self):
        self.controller.show_frame("SetBudgetPage")
        pass
    
    def view_transactions(self):
        self.controller.show_frame("ViewTransactionsPage")
        pass
