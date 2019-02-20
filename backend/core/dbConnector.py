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

def flaskify(header = [], *args):

    resultsDict = {}
    i = 0
    while i < len(header):
        resultsDict[header[i]] = []
        i += 1

    i = 0
    for arg in args:
        j = 0
        while j < len(arg):
            resultsDict[header[i]].append(arg[j])
            j += 1
        i += 1

    return resultsDict


class Database:

    def returnAllMajors(self):
        allMajorQuery = "SELECT major FROM category"
        mycursor.execute(allMajorQuery)
        allMajors = mycursor.fetchall()

        header = ['All Majors']

        allMajors = flaskify(header, allMajors)
        return allMajors

    def returnMajorID(self, major):
        majorID = "SELECT id FROM category WHERE major= '" + major + "'"
        mycursor.execute(majorID)
        myresult = mycursor.fetchone()
        return myresult[0]

    def returnMajorRequirements(self, majorID):
        courseIDQuery = "SELECT FK_course FROM appears WHERE categoryID= '" + majorID + "' AND required = 'true'"
        mycursor.execute(courseIDQuery)
        #sql query returns a list/tuple
        courseIDList = mycursor.fetchall()
        #convert the courseIDs into an array
        courseIDArray = [x for xs in courseIDList for x in xs]


        #instantiate the array for course PSID
        requiredCoursesPSID = []
        x = 0
        #while loop goes through and finds all major requirements and places into courses list
        while x < len(courseIDArray):
            peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course= '" + str(courseIDArray[x]) + "'"
            mycursor.execute(peoplesoftIDQuery)
            requiredCoursesPSID.append(mycursor.fetchone())
            x += 1
        #convert the required curses into a
        requiredCoursesPSID = [x for xs in requiredCoursesPSID for x in xs]


        #instantiate the array for course PSID
        requiredCoursesTitle = []
        x = 0
        #while loop goes through and finds all major requirements and places into courses list
        while x < len(courseIDArray):
            titleQuery = "SELECT title FROM course WHERE PK_course= '" + str(courseIDArray[x]) + "'"
            mycursor.execute(titleQuery)
            requiredCoursesTitle.append(mycursor.fetchone())
            x += 1
        #convert the required curses into a
        requiredCoursesTitle = [x for xs in requiredCoursesTitle for x in xs]


        header = ['Course ID', 'Course Title']
        reqCourses= flaskify(header, requiredCoursesPSID, requiredCoursesTitle)
        return reqCourses

    def MajorQuery(self, major):
        query = "SELECT peoplesoftID FROM course WHERE peoplesoftID LIKE '" +  major + "%'"
        mycursor.execute(query)
        myresult = mycursor.fetchall()


    #incomplete section
    def returnCourse(self, keyword):
        courseTitle = []
        keywordQuery = "SELECT title FROM course WHERE title LIKE '%" + keyword + "%'"
        mycursor.execute(keywordQuery)
        courseTitle = mycursor.fetchall()

        keywordQuery = "SELECT FK_meeting from professor WHERE professor LIKE '%" + keyword + "%'"
        mycursor.execute(keywordQuery)
        meetingTuple = mycursor.fetchall()

        return meetingTuple












db = Database()
test = db.returnAllMajors()
print (test)
