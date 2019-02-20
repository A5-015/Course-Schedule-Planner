from flask import Flask, session, redirect, url_for, escape, request
from flask import flash, render_template, request, redirect
from flask import Flask,request,render_template,jsonify
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

"""
@app.route('/status')
def status():
    if 'username' in session:
        return 'Logged in as %s' % escape(session['username'])
    return 'You are not logged in'

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        session['username'] = request.form['username']
        return redirect(url_for('index'))
    return '''
        <form method="post">
            <p><input type=text name=username>
            <p><input type=submit value=Login>
        </form>
    '''

@app.route('/logout')
def logout():
    # remove the username from the session if it's there
    session.pop('username', None)
    return redirect(url_for('index'))
"""
"""
if __name__ == "__main__":
    app.run(debug = True)
"""
