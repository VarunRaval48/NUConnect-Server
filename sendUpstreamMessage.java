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

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.SQLException;

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
    String answer = "";

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
            
            String action="";
            JSONParser jsonParser;
            JSONObject jsonObject;
            JSONArray id = new JSONArray();
            try{
                BufferedReader br = new BufferedReader(new InputStreamReader(request.getInputStream()));
                String json = br.readLine();

                jsonParser = new JSONParser();
                jsonObject = (JSONObject)jsonParser.parse(json);
                id =(JSONArray) jsonObject.get("ids");
                action = (String)jsonObject.get("action");
                
                Class.forName("com.mysql.jdbc.Driver");

                Connection c;
                Statement s;
                ResultSet r;

                c = DriverManager.getConnection("jdbc:mysql://localhost:3306/nuconnect", "root", "root");
                s = c.createStatement();


                String query;
                int len = id.size();
                if ("extra lecture".equalsIgnoreCase(action)) {
                try {
                        for(int i=0; i<len; i++){
                            query = "SELECT * from roll_reg_no where roll_no="+(String)id.get(i);
                            r = s.executeQuery(query);
                        
                            String toName = (String)id.get(i);
                            String userMessage = "Extra Lecture";
                            String fromName = "1392";
                            Sender sender = new Sender(GOOGLE_SERVER_KEY);
                            Message message = new Message.Builder().timeToLive(30)
                                            .delayWhileIdle(true).addData(MESSAGE_KEY, userMessage)
                                            .addData(REGISTER_NAME, fromName).build();
                            String regId = r.getString("reg_id");
                            Result result = sender.send(message, regId, 1);
                            answer = result.toString();
                            request.setAttribute("pushStatus", result.toString());
                    }
                } catch (IOException ioe) {
                        ioe.printStackTrace();
                         request.setAttribute("pushStatus",
                                        "RegId required: " + ioe.toString());
                } catch (Exception e) {
                        e.printStackTrace();
                         request.setAttribute("pushStatus", e.toString());
                }
                 // request.getRequestDispatcher("index.jsp")
                 //                .forward(request, response);
                }
                out.println(answer+" Sent result");
            }
            catch(ParseException p){
                    out.println("Parse Exception");
            }
            catch(SQLException e){
                e.printStackTrace();
            }
            catch(ClassNotFoundException e){
                e.printStackTrace();
            }

    }
}