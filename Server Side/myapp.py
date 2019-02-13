from flask import Flask
from flask import flash, render_template, request, redirect

app = Flask(__name__)
application = app # our hosting requires application in passenger_wsgi

@app.route("/")
def hello():
    #return "This is Hello fdsfdsfddffghghbghbgsWorld!\n"

    return render_template('index.html')

@app.route("/test")
def test():
    return "This is Htesting!\n"


if __name__ == "__main__":
    app.debug = True
