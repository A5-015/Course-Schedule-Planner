import mysql.connector

mydb = mysql.connector.connect(
  host="dummyserver.local",
  user="dummy_user",
  passwd="dummy_key",
  database="dummy_db"
)

mycursor = mydb.cursor()


class Database:

	def MajorQuery(self, major):
		query = "SELECT peoplesoftID FROM courses WHERE peoplesoftID LIKE '" +  major + "%'"
		mycursor.execute(query)
		myresult = mycursor.fetchall()
		print(myresult)


db = Database()
db.MajorQuery("ACS-UH")
