# Making a basic Bokeh line graph

# importing Bokeh
from bokeh.plotting import figure
from bokeh.io import output_file, show
from bokeh.core.validation import silence
from bokeh.core.validation.warnings import EMPTY_LAYOUT
import webbrowser

silence(EMPTY_LAYOUT, True)

# prepare some data
x=[1,2,3,4,5]
y=[6,7,8,9,10]

# prepare the output file
output_file("Line.html")

# create a figure object
f=figure()

# create Line plot
f.line: (x,y)
    
show(f)