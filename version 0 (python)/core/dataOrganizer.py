from dbConnector import Database
database = Database()

class Student:

    def setMajor(self, major):
        self.major = major
        self.majorID = database.returnMajorID(self.major)





#main function to be implemented elsewhere
student = Student()
student.setMajor("Computer Engineering")
print(student.majorID)
