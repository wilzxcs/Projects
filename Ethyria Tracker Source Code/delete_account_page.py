import tkinter as tk
from tkinter.ttk import *
from tkinter import messagebox
from database import Database

class DeleteAccountPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Delete Account", font=("Helvetica", 16)).pack(pady=10)

        Label(self, text="Username").pack(pady=5)
        self.username_entry = Entry(self)
        self.username_entry.pack(pady=5)

        Button(self, text="Delete", command=self.confirm_delete).pack(pady=20)
        Button(self, text="Back", command=lambda: controller.show_frame("AdminPage")).pack(pady=10)

    def confirm_delete(self):
        username = self.username_entry.get().strip()
        
        if not username:
            messagebox.showwarning("Input Error", "Username field must be filled.")
            return

        user = self.db.fetch_query("SELECT * FROM users WHERE username = ?", (username,))
        if user:
            confirm = messagebox.askyesno("Confirm Delete", "Are you sure you want to delete this account?")
            if confirm:
                self.db.execute_query("DELETE FROM users WHERE username = ?", (username,))
                messagebox.showinfo("Success", "Account deleted successfully!")
                self.clear_entries()
                self.controller.frames["ViewAccountsPage"].load_accounts()
        else:
            messagebox.showerror("Error", "Account not found.")
            self.clear_entries()

    def clear_entries(self):
        self.username_entry.delete(0, 'end')
