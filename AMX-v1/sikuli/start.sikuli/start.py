def enterLoginInfo():
    wait("1395245516050.png",10)
    click(Pattern("1395157404306.png").targetOffset(50,3))
    type("Comp523")
    click(Pattern("1395157441128.png").targetOffset(55,-2))
    type("learn")
    click(Pattern("1395157465014.png").targetOffset(3,3))    
def startNewIEWindow():
    App.open("c:\Program Files\Internet Explorer\iexplore.exe")
    wait("1395157058486.png",10)
    sleep(0.4)
    if exists("1395157140088.png") is not None:
        type ("152.2.129.201")
    else:
        click(Pattern("1395156745984.png").targetOffset(79,7))
        type("152.2.129.201")
    type(Key.ENTER)
 
def demo():
    startNewIEWindow()
    wait("1395245631793.png",10)
    if exists("1395158154622.png",1) is None:
        enterLoginInfo()
    wait("1395157514752.png",10)
    click("1395157522199.png")

demo()