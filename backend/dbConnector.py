import mysql.connector
from dbSecrets import HOST, USER, PWD, DB

mydb = mysql.connector.connect(
  host=HOST,
  user=USER,
  passwd=PWD,
  database=DB
)

mycursor = mydb.cursor()

class Database:

    def returnAllMajors(self):
        majors = "SELECT major FROM category"
        mycursor.execute(majors)
        myresult = mycursor.fetchall()
        return myresult

    def returnMajorID(self, major):
        majorID = "SELECT id FROM category WHERE major= '" + major + "'"
        mycursor.execute(majorID)
        myresult = mycursor.fetchone()
        return myresult[0]

    def returnMajorRequirements(self, majorID):
        courseIDQuery = "SELECT FK_course FROM appears WHERE categoryID= '" + majorID + "' AND required = 'true'"
        mycursor.execute(courseIDQuery)
        courseID = mycursor.fetchall()
        peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course= '" + courseID + "'"



    def MajorQuery(self, major):
        query = "SELECT peoplesoftID FROM courses WHERE peoplesoftID LIKE '" +  major + "%'"
        mycursor.execute(query)
        myresult = mycursor.fetchall()


db = Database()
test = db.returnMajorRequirements("72")
print (test)
