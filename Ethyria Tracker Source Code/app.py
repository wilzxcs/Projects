import tkinter as tk
from tkinter import messagebox
from tkinter.ttk import *
from login_page import LoginPage
from register_page import RegisterPage
from main_page import MainPage
from profile_page import ProfilePage
from add_transaction_page import AddTransactionPage
from set_budget_page import SetBudgetPage
from view_transactions_page import ViewTransactionsPage
from admin_page import AdminPage
from add_account_page import AddAccountPage
from view_accounts_page import ViewAccountsPage
from delete_account_page import DeleteAccountPage

class App(tk.Tk):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.title("Ethyrial Financial Tracker")
        self.current_user_id = None  # To keep track of the logged-in user

        container = tk.Frame(self)
        container.pack(side="top", fill="both", expand=True)
        container.grid_rowconfigure(0, weight=1)
        container.grid_columnconfigure(0, weight=1)

        self.frames = {}
        for F in (LoginPage, RegisterPage, MainPage, ProfilePage,
                  AddTransactionPage, SetBudgetPage, ViewTransactionsPage, AdminPage,
                  AddAccountPage, ViewAccountsPage, DeleteAccountPage):
            page_name = F.__name__
            frame = F(parent=container, controller=self)
            frame.grid(row=0, column=0, sticky="nsew")
            self.frames[page_name] = frame

        self.show_frame("LoginPage")
        
        self.protocol("WM_DELETE_WINDOW", self.on_closing)

    def show_frame(self, page_name):
        frame = self.frames[page_name]
        frame.tkraise()
        # If showing the ViewTransactionsPage, display the transactions
        if page_name == "ViewTransactionsPage" and self.current_user_id is not None:
            frame.display_transactions(self.current_user_id)
        if page_name == "ProfilePage":
            frame.load_profile()
    def on_closing(self):
        if messagebox.askokcancel("Quit", "Do you want to exit the application?"):
            self.destroy()

def main():
    app = App()
    app.geometry("800x600")
    app.mainloop()

if __name__ == "__main__":
    main()
