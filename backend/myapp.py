from flask import Flask
from flask import flash, render_template, request, redirect
import mysql.connector

app = Flask(__name__)
application = app # web hosting requires application in passenger_wsgi

mydb = mysql.connector.connect(
  host="localhost",
  user="bsimsekc_nishant",
  passwd="reppepreppep",
  database="bsimsekc_test"
)

mycursor = mydb.cursor()

query = "SELECT peoplesoftID, title, writing, isCapstone FROM course WHERE peoplesoftID LIKE '" +  "ACS-UH 1011X" + "%'"
mycursor.execute(query)
myresult = mycursor.fetchall()

print (myresult)

# List of lists
cars_list = myresult
# Output dictionary
cars_dict = {}

# Convert to dictionary

cars_dict['peopleSoftId'] = [1]
cars_dict['name'] = [1]
cars_dict['professor'] = [1]
cars_dict['description'] = [1]

# Print to see output
#print (cars_dict)

for peopleSoftId in cars_list:
    for i in peopleSoftId:
        cars_dict['peopleSoftId'].append(i)

for name in cars_list:
    for i in name:
        cars_dict['name'].append(i)

for professor in cars_list:
    for i in professor:
        cars_dict['professor'].append(i)

for description in cars_list:
    for i in description:
        cars_dict['description'].append(i)

print (cars_dict)

@app.route('/')
def result():

    #table = {'peopleSoftId':[0,1,2], 'name': ["name1","name2","name3"], 'professor': ["professor1","professor2","professor3"], 'description': ["description1","description2","description3"]}
    #table = {'ACS-UH 1011X': ['Introduction to Modern Arabic Literature', 'false', 'false']}

    return render_template('index.html', table = cars_dict)

@app.route("/test")
def test():
    return "This is Htesting!\n"

if __name__ == "__main__":
    app.run(debug = True)
