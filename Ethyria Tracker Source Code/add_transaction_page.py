import tkinter as tk
from tkinter import messagebox
from tkinter.ttk import *
from database import Database
from datetime import datetime
import re

class AddTransactionPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Transaction").place(x=100, y=100)

        self.transaction_entry = Entry(self)
        self.transaction_entry.place(x=200, y=100)
        self.transaction_entry.bind("<KeyRelease>", self.validate_entry)

        Button(self, text="Add", command=self.add_transaction).place(x=200, y=150)
        Button(self, text="Back", command=lambda: controller.show_frame("MainPage")).place(x=270, y=150)

    def validate_entry(self, event):
        value = self.transaction_entry.get()

        # Regular expression for validation
        if re.match(r'^-?\d{0,10}(\.\d{0,2})?$', value):
            return True
        else:
            # Delete the last character if it doesn't match the pattern
            self.transaction_entry.delete(len(self.transaction_entry.get()) - 1, tk.END)

    def add_transaction(self):
        value = self.transaction_entry.get().strip()
        if not value:
            messagebox.showwarning("Input Error", "Transaction value cannot be empty.")
            return

        try:
            amount = float(value)
            date = datetime.now().strftime("%Y-%m-%d")
            user_id = self.controller.current_user_id

            # Insert the transaction into the database
            self.db.execute_query(
                "INSERT INTO transactions (user_id, date, amount, total) VALUES (?, ?, ?, (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = ?) + ?)",
                (user_id, date, amount, user_id, amount)
            )

            messagebox.showinfo("Success", "Transaction added successfully!")
            self.transaction_entry.delete(0, 'end')
            self.controller.show_frame("MainPage")  # Redirect to main page after successful entry
        except ValueError:
            messagebox.showwarning("Input Error", "Please enter a valid number.")

    def clear_entries(self):
        self.transaction_entry.delete(0, 'end')
