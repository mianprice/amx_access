import simplejson as json
# the data structure that encapsulates the current
# state of the AMX control, such as the current 
# active page, active buttons.
class Model:
	# static variables:
	# maps page to a list of clickable buttons on that page
	pages = {
		'audio':[],
		'lights':[],
		'proj':['powerOff',
			'powerOn',
			'imageBlank',
			'PC1VGA1400x1050',
			'docCam',
			'laptopVGA1400x1050',
			'video',
			'laptopDVI'],
		'specialFunction':[],	
		'systemReset':[]
		};
	def __init__(self):
		self.resetButtonState();

	def resetButtonState(self):
		# maps each button to a boolean indicating
		# if it is on/off.
		self.buttons =  {
			'audio':False,
			'lights':False,
			'allOn':False,
			'nightLight':False,
			'allOff':False,
			'brightRoom':False,
			'mediumRoom':False,
			'projectionPreset':False,
			'wallCamsOn':False,
			'boardFluoroOn':False,
			'roomFluoroOn':False,
			'podiumOn':False,
			'walCamsOff':False,
			'boardFluoroOff':False,
			'roomFluoroOff':False,
			'podiumOff':False,
			'proj':False,
			'powerOff':False,
			'powerOn':False,
			'imageBlank':False,
			'PC1VGA1400x1050':False,
			'docCam':False,
			'laptopVGA1400x1050':False,
			'video':False,
			'laptopDVI':False,
			'up':False,
			'stop':False,
			'down':False,
			'systemReset':False
		}

	# given the json obejct representing the expected
	# state of buttons, calculate the difference between
	# the current state and the expected state
	# returns a list of buttons that needs to be clicked
	def diff(self,jsonFromServer):
		diff_list = []
		expected = json.loads(jsonFromServer);
		for button in self.buttons.keys():
			if self.buttons[button] != expected[button]:
				diff_list.append(button);

		return diff_list;
		

import sys
args = sys.argv
#jsonFromServer = args[1];
jsonFromServer =  '''{
	"audio.jpg":false,
	"lights.jpg":false,
	"allOn.jpg":false,
	"nightLight.jpg":false,
	"allOff.jpg":false,
	"brightRoom.jpg":false,
	"mediumRoom.jpg":false,
	"projectionPreset.jpg":false,
	"wallCamsOn.jpg":false,
	"boardFluoroOn.jpg":false,
	"roomFluoroOn.jpg":false,
	"podiumOn.jpg":false,
	"walCamsOff.jpg":false,
	"boardFluoroOff.jpg":false,
	"roomFluoroOff.jpg":false,
	"podiumOff.jpg":false,
	"proj.jpg":false,
	"powerOff.jpg":false,
	"powerOn.jpg":false,
	"imageBlank.jpg":false,
	"PC1VGA1400x1050.jpg":false,
	"docCam.jpg":false,
	"laptopVGA1400x1050.jpg":false,
	"video.jpg":false,
	"laptopDVI.jpg":false,
	"up.jpg":false,
	"stop.jpg":false,
	"down.jpg":false,
	"systemReset.jpg":false
}'''