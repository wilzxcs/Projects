import sqlite3

class Database:
    def __init__(self, db_name='database.db'):
        self.conn = sqlite3.connect(db_name)
        self.cursor = self.conn.cursor()
        self.init_db()

    def init_db(self):
        self.cursor.execute("""
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE,
                password TEXT,
                account_type TEXT CHECK(account_type IN ('admin', 'user')) DEFAULT 'user'
            )
        """)
        self.cursor.execute("""
            CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                date DATE,
                amount REAL,
                total REAL,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )
        """)
        self.cursor.execute("""
            CREATE TABLE IF NOT EXISTS budget (
                user_id INTEGER PRIMARY KEY,
                amount REAL,
                date DATE,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )
        """)
        self.conn.commit()
        self.insert_sample_data()

    def insert_sample_data(self):
        try:
            self.cursor.execute("INSERT OR IGNORE INTO users (id, username, password, account_type) VALUES (1, 'admin', 'adminpass', 'admin')")
            self.cursor.execute("INSERT OR IGNORE INTO users (id, username, password, account_type) VALUES (2, 'testuser', 'password', 'user')")
            self.conn.commit()
        except sqlite3.Error as e:
            print(f"SQL error: {e}")
            self.conn.rollback()

    def execute_query(self, query, params=()):
        try:
            self.cursor.execute(query, params)
            self.conn.commit()
        except sqlite3.Error as e:
            print(f"SQL error: {e}")
            self.conn.rollback()
            
    def fetch_one(self, query, params=()):
        self.cursor.execute(query, params)
        return self.cursor.fetchone()

    def fetch_query(self, query, params=()):
        try:
            self.cursor.execute(query, params)
            result = self.cursor.fetchall()
            return result if result else []
        except sqlite3.Error as e:
            print(f"SQL error: {e}")
            return []

    def fetch_data(self, table_name, user_id):
        try:
            self.cursor.execute(f'SELECT id, date, amount, total FROM {table_name} WHERE user_id = ?', (user_id,))
            rows = self.cursor.fetchall()
            print("Fetched rows:", rows)  # Debug print statement
            return rows
        except sqlite3.Error as e:
            print(f"SQL error: {e}")
            return []
        
    def delete_transaction(self, transaction_id):
        try:
            self.cursor.execute("DELETE FROM transactions WHERE id = ?", (transaction_id,))
            self.conn.commit()
        except sqlite3.Error as e:
            print(f"SQL error: {e}")
            self.conn.rollback()

    def __del__(self):
        self.conn.close()