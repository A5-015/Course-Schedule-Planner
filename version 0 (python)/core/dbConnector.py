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


def flaskify(header=[], *args):

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

def makeArray(infoTuple=[]):

    infoArray = [x for xs in infoTuple for x in xs]
    return infoArray

class Database:

    def returnAllMajors(self):
        allMajorQuery = "SELECT major FROM category"
        mycursor.execute(allMajorQuery)
        allMajorsList = mycursor.fetchall()
        allMajorsArray = makeArray(allMajorsList)

        header = ['All Majors']

        allMajors = flaskify(header, allMajorsArray)
        return allMajors

    def returnMajorID(self, major):
        majorIDQuery = "SELECT id FROM category WHERE major= '" + major + "'"
        mycursor.execute(majorIDQuery)
        majorID = mycursor.fetchone()
        return majorID[0]

    def returnMajorRequirements(self, majorID):
        courseIDQuery = "SELECT FK_course FROM appears WHERE categoryID= '" + \
            majorID + "' AND required = 'true'"
        mycursor.execute(courseIDQuery)
        # sql query returns a list/tuple
        courseIDList = mycursor.fetchall()
        # convert the courseIDs into an array
        courseIDArray = makeArray(courseIDList)

        # instantiate the array for course PSID
        requiredCoursesPSID = []
        x = 0
        # while loop goes through and finds all major requirements and places into courses list
        while x < len(courseIDArray):
            peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course= '" + \
                str(courseIDArray[x]) + "'"
            mycursor.execute(peoplesoftIDQuery)
            requiredCoursesPSID.append(mycursor.fetchone())
            x += 1
        # convert the required curses into a
        requiredCoursesPSID = makeArray(requiredCoursesPSID)

        # instantiate the array for course PSID
        requiredCoursesTitle = []
        x = 0
        # while loop goes through and finds all major requirements and places into courses list
        while x < len(courseIDArray):
            titleQuery = "SELECT title FROM course WHERE PK_course= '" + \
                str(courseIDArray[x]) + "'"
            mycursor.execute(titleQuery)
            requiredCoursesTitle.append(mycursor.fetchone())
            x += 1
        # convert the required courses list into an array
        requiredCoursesTitle = makeArray(requiredCoursesTitle)

        header = ['Course ID', 'Course Title']
        reqCoursesDict = flaskify(header, requiredCoursesPSID,
                              requiredCoursesTitle)
        return reqCoursesDict

    # def MajorQuery(self, major):
    #     query = "SELECT peoplesoftID FROM course WHERE peoplesoftID LIKE '" + major + "%'"
    #     mycursor.execute(query)
    #     myresult = mycursor.fetchall()

    # incomplete section

    def returnCourses(self, keyword):

        #Simple database query through course list
        keywordQuery = "SELECT title FROM course WHERE title LIKE '%" + keyword + "%'"
        mycursor.execute(keywordQuery)
        courseTitleList = mycursor.fetchall()
        courseTitleArray = makeArray(courseTitleList)

        #Database query logic
        keywordQuery = "SELECT FK_meeting from professor WHERE professor LIKE '%" + keyword + "%'"
        mycursor.execute(keywordQuery)
        meetingList = mycursor.fetchall()

        meetingArr

        return meetingTuple


db = Database()
test = db.returnMajorID("Computer Engineering")
print(test)
