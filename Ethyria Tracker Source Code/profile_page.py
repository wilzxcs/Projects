import tkinter as tk
import sqlite3
from tkinter import messagebox
from tkinter.ttk import *
from database import Database

# class ProfilePage(tk.Frame):
#     def __init__(self, parent, controller):
#         super().__init__(parent)
#         self.controller = controller
#         self.db = Database()
        
#         Label(self, text="Username").place(x=220, y=100)
#         self.username_entry = Entry(self)
#         self.username_entry.place(x=300, y=100)

#         Label(self, text="Password").place(x=220, y=160)
#         self.password_entry = Entry(self, show="*")
#         self.password_entry.place(x=300, y=160)

#         Button(self, text="Edit", command=self.edit_profile).place(x=300, y=220)
#         Button(self, text="Back", command=lambda: controller.show_frame("MainPage")).place(x=220, y=220)
#         Button(self, text="Logout", command=self.logout).place(x=380, y=220)

#     def edit_profile(self):
#         username = self.username_entry.get().strip()
#         password = self.password_entry.get().strip()

#         if not username and not password:
#             messagebox.showwarning("Input Error", "Username and/or password fields must be filled.")
#             return

#         user_id = self.controller.current_user_id

#         # Initialize query and parameters
#         queries = []
#         params = []

#         if username:
#             queries.append("UPDATE users SET username = ? WHERE id = ?")
#             params.append((username, user_id))

#         if password:
#             encrypted_password = sha256(password.encode()).hexdigest()
#             queries.append("UPDATE users SET password = ? WHERE id = ?")
#             params.append((encrypted_password, user_id))

#         try:
#             # Execute all the queries
#             for query, param in zip(queries, params):
#                 self.db.execute_query(query, param)
            
#             success_messages = []
#             if username:
#                 success_messages.append("Username updated successfully!")
#             if password:
#                 success_messages.append("Password updated successfully!")

#             messagebox.showinfo("Success", "\n".join(success_messages))
#             self.controller.frames["ViewAccountsPage"].load_accounts()
#             self.clear_entries()
            
#         except sqlite3.IntegrityError:
#             messagebox.showerror("Error", "This username is already registered.")
#             self.clear_entries()
#         except Exception as e:
#             messagebox.showerror("Error", str(e))

#     def logout(self):
#         self.controller.current_user_id = None
#         self.controller.show_frame("LoginPage")

#     def load_profile(self):
#         try:
#             user_id = self.controller.current_user_id
#             user_data = self.db.fetch_one("SELECT username FROM users WHERE id = ?", (user_id,))
#             if user_data:
#                 self.username_entry.delete(0, 'end')
#                 self.username_entry.insert(0, user_data[0])
#         except Exception as e:
#             messagebox.showerror("Error", str(e))

#     def clear_entries(self):
#         self.username_entry.delete(0, 'end')
#         self.password_entry.delete(0, 'end')
import tkinter as tk
from tkinter import messagebox
from tkinter.ttk import *
from database import Database

class ProfilePage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()
        
        Label(self, text="Username").place(x=220, y=100)
        self.username_entry = Entry(self)
        self.username_entry.place(x=300, y=100)

        Label(self, text="Password").place(x=220, y=160)
        self.password_entry = Entry(self, show="*")
        self.password_entry.place(x=300, y=160)

        Button(self, text="Edit", command=self.edit_profile).place(x=300, y=220)
        Button(self, text="Back", command=lambda: controller.show_frame("MainPage")).place(x=220, y=220)
        Button(self, text="Logout", command=self.logout).place(x=380, y=220)

    def edit_profile(self):
        new_password = self.password_entry.get().strip()
        new_username = self.username_entry.get().strip()

        if not new_username and not new_password:
            messagebox.showwarning("Input Error", "Username and/or password fields must be filled.")
            return

        user_id = self.controller.current_user_id

        try:
            old_username = self.get_old_username(user_id)
            if new_username == old_username:
                messagebox.showinfo("Info", "New username is the same as the old username. No action taken.")
                return
        
            if new_username:
                if self.username_exists(new_username):
                    messagebox.showerror("Error", "This username is already registered.")
                    return
                self.update_username(new_username, user_id)
        
            if new_password:
                self.update_password(new_password, user_id)

            messagebox.showinfo("Success", "Profile updated successfully!")
            self.back_to_main_page()
        except sqlite3.IntegrityError:
            messagebox.showerror("Error", "This username is already registered.")
        except Exception as e:
            messagebox.showerror("Error", str(e))
            
    def get_old_username(self, user_id):
        user_data = self.db.fetch_one("SELECT username FROM users WHERE id = ?", (user_id,))
        if user_data:
            return user_data[0]
        else:
            raise ValueError("User not found.")
        
    def update_username(self, new_username, user_id):
        self.db.execute_query("UPDATE users SET username = ? WHERE id = ?", (new_username, user_id))

    def update_password(self, new_password, user_id):
        self.db.execute_query("UPDATE users SET password = ? WHERE id = ?", (new_password, user_id))

    def logout(self):
        self.controller.current_user_id = None
        self.controller.show_frame("LoginPage")

    def back_to_main_page(self):
        self.controller.show_frame("MainPage")
        
    def load_profile(self):
        try:
            user_id = self.controller.current_user_id
            user_data = self.db.fetch_one("SELECT username FROM users WHERE id = ?", (user_id,))
            if user_data:
                self.username_entry.delete(0, 'end')
                self.username_entry.insert(0, user_data[0])
        except Exception as e:
            messagebox.showerror("Error", str(e))

    def username_exists(self, username):
        result = self.db.fetch_one("SELECT 1 FROM users WHERE username = ?", (username,))
        return result is not None
