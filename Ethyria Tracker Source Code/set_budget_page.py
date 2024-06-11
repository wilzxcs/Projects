import tkinter as tk
import re
from tkinter import messagebox
from tkinter.ttk import *
from database import Database
from datetime import datetime

class SetBudgetPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Budget").place(x=100, y=100)
        self.budget_entry = Entry(self)
        self.budget_entry.place(x=200, y=100)
        self.budget_entry.bind("<KeyRelease>", self.validate_entry)

        Button(self, text="Set", command=self.set_budget).place(x=200, y=150)
        Button(self, text="Compare", command=self.compare_budget).place(x=300, y=150)  # New Compare button
        Button(self, text="Back", command=lambda: controller.show_frame("MainPage")).place(x=400, y=150)  # Adjusted Back button

    def validate_entry(self, event):
        value = self.budget_entry.get()

        # Regular expression for validation of positive numbers with up to 2 decimal places
        if re.match(r'^\d{0,10}(\.\d{0,2})?$', value):
            return True
        else:
            # Delete the last character if it doesn't match the pattern
            self.budget_entry.delete(len(self.budget_entry.get()) - 1, tk.END)

    def set_budget(self):
        budget_value = self.budget_entry.get().strip()

        if not budget_value:
            messagebox.showwarning("Input Error", "Budget value cannot be empty.")
            return

        try:
            budget = float(budget_value)
            user_id = self.controller.current_user_id
            date = datetime.now().strftime("%Y-%m-%d")

            # Insert or update the budget for the user
            self.db.execute_query(
                "INSERT OR REPLACE INTO budget (user_id, amount, date) VALUES (?, ?, ?)",
                (user_id, budget, date)
            )

            messagebox.showinfo("Success", "Budget set successfully!")
            self.clear_entries()
            # self.controller.show_frame("MainPage")
        except ValueError:
            messagebox.showwarning("Input Error", "Please enter a valid number.")
        except Exception as e:
            messagebox.showerror("Database Error", f"An error occurred: {e}")

    def compare_budget(self):
        try:
            user_id = self.controller.current_user_id

            # Fetch the total expenses for the user
            total_expenses = self.db.fetch_one("SELECT SUM(amount) FROM transactions WHERE user_id = ?", (user_id,))[0]

            if total_expenses is None:
                total_expenses = 0

            # Fetch the set budget for the user
            set_budget_result = self.db.fetch_one("SELECT amount FROM budget WHERE user_id = ?", (user_id,))
            if set_budget_result is None:
                messagebox.showwarning("No Budget Set", "No budget has been set for the current user.")
                return
        
            set_budget = set_budget_result[0]

            # Calculate the difference between the set budget and the total expenses
            difference = set_budget - total_expenses

            messagebox.showinfo("Budget Comparison", f"Set Budget: ${set_budget:.2f}\nTotal Expenses: ${total_expenses:.2f}\nDifference: ${difference:.2f}")
        except Exception as e:
            messagebox.showerror("Error", str(e))

            
    def clear_entries(self):
        self.budget_entry.delete(0, 'end')
