import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.simple.*;
import org.json.simple.parser.*;
import com.google.android.gcm.server.Message;
import com.google.android.gcm.server.MulticastResult;
import com.google.android.gcm.server.Result;
import com.google.android.gcm.server.Sender;

@WebServlet("/GCMNotification")

class messageInfo{
    List<String> username;
}

public class sendUpstreamMessage extends HttpServlet {
	private static final long serialVersionUID = 1L;

	// Put your Google API Server Key here
	private static final String GOOGLE_SERVER_KEY = "AIzaSyBO1EeoxA7GqZ8iRvQZEdeX2rtL9266Lts";
	static final String REGISTER_NAME = "name";
	static final String MESSAGE_KEY = "message";
	static final String TO_NAME = "toName";
	static final String REG_ID_STORE = "GCMRegId.txt";

	public sendUpstreamMessage() {
		super();
	}

	protected void doGet(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);

	}

	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {

            PrintWriter out = response.getWriter();
            
            String action="", id;
            JSONParser jsonParser;
            JSONObject jsonObject;
                try{
                
                BufferedReader br = new BufferedReader(new InputStreamReader(request.getInputStream()));
                String json = br.readLine();

                jsonParser = new JSONParser();
                jsonObject = (JSONObject)jsonParser.parse(json);
                id = (String)jsonObject.get("ids");
                action = (String)jsonObject.get("action");
                }
                catch(ParseException p){
                    out.println("Parse Exception");
                }
                
                out.println(action);
//		if ("shareRegId".equalsIgnoreCase(action)) {
//
//			writeToFile(request.getParameter("name"),
//					request.getParameter("regId"));
//			request.setAttribute("pushStatus",
//					"GCM Name and corresponding RegId Received.");
//			request.getRequestDispatcher("index.jsp")
//					.forward(request, response);
//
//		} 
//                if ("sendMessage".equalsIgnoreCase(action)) {
//                    
//			try {
//				String fromName = request.getParameter(REGISTER_NAME);
//				String toName = request.getParameter(TO_NAME);
//				String userMessage = request.getParameter(MESSAGE_KEY);
//				Sender sender = new Sender(GOOGLE_SERVER_KEY);
//				Message message = new Message.Builder().timeToLive(30)
//						.delayWhileIdle(true).addData(MESSAGE_KEY, userMessage)
//						.addData(REGISTER_NAME, fromName).build();
//				Map regIdMap = readFromFile();
//				String regId = regIdMap.get(toName).toString();
//				Result result = sender.send(message, regId, 1);
//				request.setAttribute("pushStatus", result.toString());
//			} catch (IOException ioe) {
//				ioe.printStackTrace();
//				request.setAttribute("pushStatus",
//						"RegId required: " + ioe.toString());
//			} catch (Exception e) {
//				e.printStackTrace();
//				request.setAttribute("pushStatus", e.toString());
//			}
//			request.getRequestDispatcher("index.jsp")
//					.forward(request, response);
//		}
//                else if ("multicast".equalsIgnoreCase(action)) {
//
//			try {
//				String fromName = request.getParameter(REGISTER_NAME);
//				String userMessage = request.getParameter(MESSAGE_KEY);
//				Sender sender = new Sender(GOOGLE_SERVER_KEY);
//				Message message = new Message.Builder().timeToLive(30)
//						.delayWhileIdle(true).addData(MESSAGE_KEY, userMessage)
//						.addData(REGISTER_NAME, fromName).build();
//				Map regIdMap = readFromFile();
//
//				List regIdList = new ArrayList();
//
//				for (Entry entry : regIdMap.entrySet()) {
//					regIdList.add(entry.getValue());
//				}
//
//				MulticastResult multiResult = sender
//						.send(message, regIdList, 1);
//				request.setAttribute("pushStatus", multiResult.toString());
//			} catch (IOException ioe) {
//				ioe.printStackTrace();
//				request.setAttribute("pushStatus",
//						"RegId required: " + ioe.toString());
//			} catch (Exception e) {
//				e.printStackTrace();
//				request.setAttribute("pushStatus", e.toString());
//			}
//			request.getRequestDispatcher("index.jsp")
//					.forward(request, response);
//		}
	}

	private void writeToFile(String name, String regId) throws IOException {
		Map regIdMap = readFromFile();
		regIdMap.put(name, regId);
		PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter(
				REG_ID_STORE, false)));
//		for (Map.Entry entry : regIdMap.entrySet()) {
//			out.println(entry.getKey() + "," + entry.getValue());
//		}
		out.println(name + "," + regId);
		out.close();

	}

	private Map readFromFile() throws IOException {
		BufferedReader br = new BufferedReader(new FileReader(REG_ID_STORE));
		String regIdLine = "";
		Map regIdMap = new HashMap();
		while ((regIdLine = br.readLine()) != null) {
			String[] regArr = regIdLine.split(",");
			regIdMap.put(regArr[0], regArr[1]);
		}
		br.close();
		return regIdMap;
	}
}
