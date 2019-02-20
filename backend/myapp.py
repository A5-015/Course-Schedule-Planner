from flask import Flask
from flask import flash, render_template, request, redirect
import mysql.connector

app = Flask(__name__)
application = app # web hosting requires application in passenger_wsgi

##########################################################################
########################### Table tester START ###########################
##########################################################################
mydb = mysql.connector.connect(
  host="localhost",
  user="bsimsekc_nishant",
  passwd="reppepreppep",
  database="bsimsekc_test"
)

mycursor = mydb.cursor()

query = "SELECT peoplesoftID, title, writing FROM course WHERE peoplesoftID LIKE '" +  "ACS-UH 2210JX" + "%'"
mycursor.execute(query)
myresult = mycursor.fetchall()

resultsDict = {'peopleSoftId': [], 'name': [], 'professor': []}

returnedResultLength = len(myresult)
i = 0
while i < returnedResultLength:
    resultsDict['peopleSoftId'].append(myresult[i][0])
    resultsDict['name'].append(myresult[i][1])
    resultsDict['professor'].append(myresult[i][2])
    i = i + 1
##########################################################################
########################### Table tester END #############################
##########################################################################

@app.route('/')
def result():

    return render_template('index.html', table = resultsDict)

@app.route("/test")
def test():
    return "This is Htesting!\n"

if __name__ == "__main__":
    app.run(debug = True)
