from flask import Flask, render_template, request, send_file
from werkzeug.utils import secure_filename
import csv, pandas
from geopy.geocoders import ArcGIS

nom = ArcGIS()


app=Flask(__name__)

@app.route("/")
def index():
    return render_template("index.html")


@app.route("/success", methods=['POST'])
def success():
    global file
    if request.method=='POST':
        file=request.files["file"]
        file.save(secure_filename("updated"+file.filename))

        if file.filename.lower().endswith('.csv')==False:
            return render_template('index.html', text="You tried to upload a non-csv file.  Please try again")

        df=pandas.read_csv(file.filename)


        if "address" and "Address" not in df.columns:
            return render_template("index.html" , text="The File You Selected had No Addresses to Locate")

    df["Latitude"]=round(df["Address"].apply(nom.geocode).apply(lambda x: x.latitude if x != None else None),4)
    df["Longitude"]=round(df["Address"].apply(nom.geocode).apply(lambda x: x.longitude if x != None else None),4)
    df.to_csv("updated" + file.filename, index=False)
    return render_template("index.html", btn="download.html", data=df.to_html())



@app.route("/download")
def download():
    return send_file("updated" + file.filename, attachment_filename="newfile.csv", as_attachment=True)

if __name__ == '__main__':
    app.debug=True
    app.run()
