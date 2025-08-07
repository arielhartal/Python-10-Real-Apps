from tkinter import *

window=Tk()



def kg_in_grams():
    grams=float(e1_value.get())*1000
    t1.delete("1.0", END)
    t1.insert(END,grams)

def kg_in_pounds():
    pounds=float(e1_value.get())*2.20462
    t2.delete("1.0", END)
    t2.insert(END,pounds)

def kg_in_ounces():
    pounds=float(e1_value.get())*35.274
    t3.delete("1.0", END)
    t3.insert(END,pounds)



all_commands = lambda:[kg_in_grams(),kg_in_pounds(),kg_in_ounces()]

b1=Button(window,text="Convert",command=all_commands)
b1.grid(row=0,column=2)

t1=Text(window,height=1,width=20)
t1.grid(row=1,column=0)

t2=Text(window,height=1,width=20)
t2.grid(row=1,column=1)

t3=Text(window,height=1,width=20)
t3.grid(row=1,column=2)

e1_value=StringVar()
e1=Entry(window,textvariable=e1_value)
e1.grid(row=0,column=1)


l1_value=StringVar()
l1 = Label(window,textvariable=l1_value)
l1_value.set("Kg")
l1.grid(row=0,column=0)


window.mainloop()
