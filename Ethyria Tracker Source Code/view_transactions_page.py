import tkinter as tk
from tkinter.ttk import Treeview, Button, Label
from tkinter import messagebox
from database import Database

class ViewTransactionsPage(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller
        self.db = Database()

        Label(self, text="Ethyrial Financial Tracker", font=("Helvetica", 16)).pack(pady=10)

        # Create Treeview widget
        columns = ("ID", "Date", "Amount", "Total")
        self.tree = Treeview(self, columns=columns, show="headings")
        self.tree.heading("ID", text="ID", command=lambda: self.sort_column("ID"))
        self.tree.heading("Date", text="Date", command=lambda: self.sort_column("Date"))
        self.tree.heading("Amount", text="Amount", command=lambda: self.sort_column("Amount"))
        self.tree.heading("Total", text="Total", command=lambda: self.sort_column("Total"))

        self.tree.pack(fill=tk.BOTH, expand=True, padx=20, pady=20)

        # Back and Delete buttons
        Button(self, text="Back", command=lambda: controller.show_frame("MainPage")).pack(side=tk.LEFT, padx=20, pady=10)
        Button(self, text="Delete", command=self.delete_selected).pack(side=tk.RIGHT, padx=20, pady=10)

        self.sort_column("ID", descending=True)  # Initial sorting by ID in descending order

    def display_transactions(self, user_id):
        for item in self.tree.get_children():
            self.tree.delete(item)

        rows = self.db.fetch_data('transactions', user_id)
        print("Displaying transactions for user_id:", user_id)

        for row in rows:
            self.tree.insert('', tk.END, values=row)

    def sort_column(self, column, descending=False):
        data = [(self.tree.set(child, column), child) for child in self.tree.get_children("")]
        data.sort(key=lambda x: (float(x[0]) if column in ["Amount", "Total"] else x[0]), reverse=descending)

        for index, (_, child) in enumerate(data):
            self.tree.move(child, "", index)

        self.tree.heading(column, command=lambda: self.sort_column(column, not descending))

    def delete_selected(self):
        selected_item = self.tree.selection()
        if selected_item:
            item_values = self.tree.item(selected_item)["values"]
            transaction_id = item_values[0]
            print(f"Selected transaction ID for deletion: {transaction_id}")  # Debug print statement
            self.db.delete_transaction(transaction_id)
            self.tree.delete(selected_item)
            messagebox.showinfo("Success", "Transaction deleted successfully!")
            self.display_transactions(self.controller.current_user_id)
        else:
            messagebox.showwarning("Select Item", "Please select a transaction to delete.")


class Controller:
    def __init__(self):
        self.current_user_id = 2  # Set a valid user ID

def main():
    root = tk.Tk()
    controller = Controller()
    view_transactions_page = ViewTransactionsPage(root, controller)
    view_transactions_page.pack(fill=tk.BOTH, expand=True)
    root.mainloop()

if __name__ == "__main__":
    main()
