import org.sikuli.basics.SikuliXforJython
from sikuli import *

# global constants
JSON_LIB_PATH = 'C:\Users\jixinge\Documents\america\comp523\wamp\www\sikuli\amx.sikuli\simplejson'
IE_PATH = 'c:\Program Files\Internet Explorer\iexplore.exe'
AMX_ADDRESS = 'http://localhost:8090/AMX-v1/AMXmainStatic.html'
IMG_EXT = '.png'


# class that handles sikuli commands
# a singleton
class SikuliController:
    # the singleton instance
    _instance = None
    @staticmethod
    def get_instance():
        if SikuliController._instance is None:
            SikuliController._instance = SikuliController()
        return SikuliController._instance;
    
    def __init__(self):
        self.init_states();
        self.check_IE_open();
        
    # initializes instance variables associated with 
    # the sikuli control    
    def init_states(self):
        # maps button to the page it belongs to
        self.buttons = dict();
        self.buttons = {
            'powerOffF':'proj',
            'powerOnF':'proj',
            'imageBlankF':'proj',
            'cpu1F':'proj',
            'cpu2F':'proj',
            '1DVIF':'proj',
            '1VGAF':'proj',
            'codecF':'proj',
            'catvInF':'proj',
            'powerOffR':'proj',
            'powerOnR':'proj',
            'imageBlankR':'proj',
            'cpu1R':'proj',
            'cpu2R':'proj',
            '1DVIR':'proj',
            '1VGAR':'proj',
            'codecR':'proj',
            'catvInR':'proj',
            'allOn':'lights',
            'nightLight':'lights',
            'allOff':'lights',
            'brightRoom':'lights',
            'mediumRoom':'lights',
            'dimRoom':'lights',
            'projectionPreset':'lights',
            'lBoardOn':'lights',
            'lBoardOff':'lights',
            'rBoardOn':'lights',
            'rBoardOff':'lights',
            'mBoardOn':'lights',
            'mBoardOff':'lights',
            'podiumOn':'lights',
            'podiumOff':'lights',
            'dimmerControl':'lights',
            'resetLighting':'lights',
            'upF':'screens',
            'stopF':'screens',
            'downF':'screens',
            'upR':'screens',
            'stopR':'screens',
            'downR':'screens',
            'menu':'tuner',
            'mUp':'tuner',
            'mDown':'tuner',
            'mLeft':'tuner',
            'mRight':'tuner',
            'mEnter':'tuner',
            'mExit':'tuner',
            'one':'tuner',
            'two':'tuner',
            'three':'tuner',
            'four':'tuner',
            'five':'tuner',
            'six':'tuner',
            'seven':'tuner',
            'eight':'tuner',
            'nine':'tuner',
            'dash':'tuner',
            'zero':'tuner',
            'info':'tuner',
            'numEnter':'tuner',
            'clear':'tuner',
            'chUp':'tuner',
            'previous':'tuner',
            'chDown':'tuner',
            'off':'tuner',
            'on':'tuner',
            'up':'tuner',
            'down':'tuner',
            'raisePCLevel':'audio',
            'lowerPCLevel':'audio',
            'raiseMicLevel':'audio',
            'lowerMicLevel':'audio',
            'mute':'audio',
            'testTone':'audio',
            'powerOff3':'proj3',
            'powerOn3':'proj3',
            'imageBlank3':'proj3',
            'computer13':'proj3',
            'computer23':'proj3',
            '1aptopDVI3':'proj3',
            '1aptopVGA3':'proj3',
            'codec3':'proj3',
            'catvInput3':'proj3',
            'proj':'',
            'audio':'',
            'lights':'',
            'systemReset':'',
            'specialFunction':'',
            'tuner':'',
            'proj3':'',
            'screens':''
        };
        # the app instance of the ie window
        self.ie_window = App(IE_PATH)
    # checks if the ie window is open, if not, use sikuli
    # to open it and go to the amx page
    def check_IE_open(self):
        if self.ie_window is None or not self.ie_window.window():
            ie = App.open(IE_PATH);
            wait(Pattern("ie_header.png").similar(0.50),10)
            sleep(0.4)
            if exists(Pattern("about_blank_selected.png").similar(0.49)) is not None:
                type(AMX_ADDRESS)
            else:
                click(Pattern("ie_header2.png").similar(0.50).targetOffset(79,7))
                type(AMX_ADDRESS)
            type(Key.ENTER)
            #wait(Pattern("navi.png").similar(0.66),10)
            wait(1)
            self.ie_window = ie
        # bring ie to the foreground
        self.ie_window.focus()
        try:
            wait("static_page.png")
        except:
            click(Pattern("ie_header2.png").similar(0.50).targetOffset(79,7))
            type(Key.ENTER)
    # attempts to navigate to the specified page
    # returns false if failed
    def navigate_to_page(self,page):
        self.check_IE_open()
        img_to_click = page+IMG_EXT
        img_to_wait = page+'Page'+IMG_EXT
        try:
            click(Pattern(img_to_click).similar(0.60))
            wait(Pattern(img_to_wait).similar(0.60),2)
            return True
        except:
            return False
        return False
        
            
    # performs the button click on the specified button
    # on is a boolean indicating the expected state after the click
    # returns false if failed, true if success
    def click_button(self,button):
        self.check_IE_open()
        page = self.buttons[button]
        if page is not None and page:
            if self.navigate_to_page(page):
                img_to_click = button+IMG_EXT
                try:
                    click(Pattern(img_to_click).similar(0.60))
                    return True
                except:
                    return False
        return False
