"""
import cv2
import glob

imgs=glob.glob("*.jpg")

img1=cv2.imread("galaxy.jpg",0)
img2=cv2.imread("kangaroos-rain-australia_71370_990x742.jpg", 0)
img3=cv2.imread("Lighthouse.jpg",0)
img4=cv2.imread("Moon sinking, sun rising.jpg",0)

imgs=[img1,img2,img3,img4]


for img in imgs:
    img=cv2.imread(img,0)
    resized_image=cv2.resize(img, (100,100))
    cv2.imshow("", resized_image)    
    cv2.waitKey(0)
    cv2.destroyAllWindows()
    cv2.imwrite("resized_"+img,resized_image)

print(type(img))
print(img)
print(img.shape)
print(img.ndim)




#resized_image=cv2.resize(img, (int(img.shape[1]/2), int(img.shape[0]/2)))
#cv2.imshow("Galaxy", resized_image)
#cv2.imwrite("Galaxy_resized.jpg", resized_image)
#cv2.waitKey(0)
#cv2.destroyAllWindows()
"""
import cv2
import glob

images=glob.glob("*.jpg")

for image in images:
    img=cv2.imread(image,0)
    re=cv2.resize(img,(100,100))
    cv2.imshow("Hey",re)
    cv2.waitKey(500)
    cv2.destroyAllWindows()
    cv2.imwrite("resized_"+image,re)