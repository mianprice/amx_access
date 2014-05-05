import SocketServer
import os
import sys
from sikuliController import SikuliController


class MyTCPHandler(SocketServer.BaseRequestHandler):

    def handle(self):
        self.data = self.request.recv(1024).strip()
        #print "{} wrote:", self.client_address
        print self.data
        button = self.data
        if button:
            sikuli_control = SikuliController.get_instance();
            success = sikuli_control.click_button(button);
            print success

        self.finish()


if __name__ == "__main__":
    sys.path.append("C:\wamp\www\AMX-v1\sikuli")
    HOST, PORT = "localhost", 8080
    server = SocketServer.ThreadingTCPServer((HOST, PORT), MyTCPHandler)
    server.serve_forever()
