import mysql.connector
import numpy as np
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
        courseIDTuple = mycursor.fetchall()
        courseIDList = list(courseIDTuple)
        courseIDArray = [x for xs in courseIDList for x in xs]
        requiredCoursesTuple = []
        print (courseIDArray)
        x = 0
        while x < len(courseIDArray):
            peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course= '" + str(courseIDArray[x]) + "'"
            mycursor.execute(peoplesoftIDQuery)
            requiredCoursesTuple.append(mycursor.fetchone())
            x += 1
        requiredCoursesArray = [x for xs in requiredCoursesTuple for x in xs]
        return requiredCoursesArray

    def returnCourse(self, keyword):
        courseTitle = []
        keywordQuery = "SELECT title FROM course WHERE title LIKE '%" + keyword + "%'"
        mycursor.execute(keywordQuery)
        courseTitle = mycursor.fetchall()
        return courseTitle





        # for x in range(len(courseIDArray)):
        #     peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course= '" + str(courseIDArray[x]) + "'"
        #     mycursor.execute(peoplesoftIDQuery)
        #     requiredCourses[x] = mycursor.fetchone()
        return requiredCourses

    def MajorQuery(self, major):
        query = "SELECT peoplesoftID FROM course WHERE peoplesoftID LIKE '" +  major + "%'"
        mycursor.execute(query)
        myresult = mycursor.fetchall()




db = Database()
test = db.returnCourse("anthr")
print (test)
