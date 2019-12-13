var commands = [];
var commandList = [];

class command {
    constructor(name, description, action) {
        this.name = name;
        this.description = description;
        this.action = action;
        commands[name] = this;
        commandList.push(name);
    }
}

function execute(command, args = ""){
    console.log(sessionStorage.getItem('auth'));
    console.log(typeof(sessionStorage.getItem('auth')));
    if (typeof(sessionStorage.getItem('auth')) !== 'object'){
        var name = sessionStorage.getItem('auth');
    } else {
        var name = 'guest';
    }
    
    if (args == ""){
        addLine(name + " : " + command);
    } else {
        var argsToString = args.join(' ');
        addLine(name + " : " + command + " " + argsToString)
    }
    if (typeof(commands[command]) !== 'undefined') {
        commandExist = true;
        commands[command].action(args);
    } else {
        addLine("Command \""+command+"\" does not exists. Type \"help\" for informations.")
    }
}

var clr = new command("clr", "Clear the screen", function(){
    mainWin.innerHTML = "";
});

var intro = new command("intro", "Display startup intro", function() {
    addLine("");
    addLine("      ▄███████▄  ▄█          ▄████████  ▄████████    ▄████████    ▄█    █▄     ▄██████▄   ▄█       ████████▄     ▄████████    ▄████████ ");
    addLine("     ███    ███ ███         ███    ███ ███    ███   ███    ███   ███    ███   ███    ███ ███       ███   ▀███   ███    ███   ███    ███ ");
    addLine("     ███    ███ ███         ███    ███ ███    █▀    ███    █▀    ███    ███   ███    ███ ███       ███    ███   ███    █▀    ███    ███ ");
    addLine("     ███    ███ ███         ███    ███ ███         ▄███▄▄▄      ▄███▄▄▄▄███▄▄ ███    ███ ███       ███    ███  ▄███▄▄▄      ▄███▄▄▄▄██▀ ");
    addLine("   ▀█████████▀  ███       ▀███████████ ███        ▀▀███▀▀▀     ▀▀███▀▀▀▀███▀  ███    ███ ███       ███    ███ ▀▀███▀▀▀     ▀▀███▀▀▀▀▀   ");
    addLine("     ███        ███         ███    ███ ███    █▄    ███    █▄    ███    ███   ███    ███ ███       ███    ███   ███    █▄  ▀██████████▄ ");
    addLine("     ███        ███▌    ▄   ███    ███ ███    ███   ███    ███   ███    ███   ███    ███ ███▌    ▄ ███   ▄███   ███    ███   ███    ███ ");
    addLine("    ▄████▀      █████▄▄██   ███    █▀  ████████▀    ██████████   ███    █▀     ▀██████▀  █████▄▄██ ████████▀    ██████████   ███    ███ ");
    addLine("                ▀                                                                        ▀                                   ███    ███▄");
    addLine("");
    addLine("Type \"help\" for informations.");
});

var help = new command("help", "Display a list of commands. Add a command as argument for a short description.", function (commandToExplain = ""){
    console.log(commandToExplain);
    console.log(typeof(commands[commandToExplain]));
    if (commandToExplain == ""){
        var string = "";
        commandList.forEach(commandName => {
            string += commandName + ", ";
        });
        var result = string.slice(0, -2);
    } else if (typeof(commands[commandToExplain]) !== 'undefined'){
        var result = (commands[commandToExplain].description);
    } else {
        var result = "No description for this command."
    }
    addLine(result);
});

var scanmap = new command("scanmap", "Scan the area and print an interactive map on screen.", function(){
    oreMap();
});

var login = new command("login", "Log into an existing account", function(){
    if (typeof(sessionStorage.getItem('auth')) !== 'object'){
        addLine("You are already logged in.")
    } else {
        addLine("Enter username :")
        waitForUserInput(function(input){
            username = input;
            addLine("Enter password :");
            waitForUserInput(function(input){
                password = input;
                var reqUsername = new XMLHttpRequest();
                var url = "index.php?p=authentification.login&username=" + username + "&password=" + password;
                reqUsername.open("GET", url);
                reqUsername.addEventListener("load", function () {
                    if (reqUsername.responseText == "username"){
                        addLine("Username not found.");
                    } else if (reqUsername.responseText == "password"){
                        addLine("Wrong password.");
                    } else {
                        array = JSON.parse(reqUsername.responseText)
                        sessionStorage.setItem('auth', array['auth']);
                        sessionStorage.setItem('authId', array['authId']);
                        addLine("Login successful.");
                    }
                });
                reqUsername.send(null);
            })
        })
    }
});

var disconnect = new command("disconnect", "Closes session.", function(){
    if (typeof(sessionStorage.getItem('auth')) == 'object'){
        addLine("You are already disconnected.")
    } else {
        var req = new XMLHttpRequest();
        var url = "index.php?p=authentification.disconnect";
        req.open("GET", url);
        req.addEventListener("load", function () {
            if(req.responseText){
                sessionStorage.clear();
                addLine("Successfully disconnected.");
            } else {
                addLine("Error.");
            }
        });
        req.send(null);
    }
})

var register = new command("register", "Create a new account when not signed in.", function(){
    if (typeof(sessionStorage.getItem('auth')) !== 'object'){
        addLine("You can't register, you are already logged in.")
    } else {
        addLine("Enter username :")
        waitForUserInput(function(input){
            username = input;
            var reqUsername = new XMLHttpRequest();
            var url = "index.php?p=authentification.checkUsername&username=" + username;
            reqUsername.open("GET", url);
            reqUsername.addEventListener("load", function () {
                if (reqUsername.responseText !== "true"){
                    addLine("An account with this username already exists.");
                } else if (username.length < 3 || username.length > 25){
                    addLine("Usernames must contain between 3 and 25 characters.");
                } else if (!/^[a-zA-Z\-]+$/.test(username)){
                    addLine("invalid username. Please enter a valid username.");
                } else {
                    addLine("Enter password :")
                    waitForUserInput(function(input){
                        password = input;
                        if (password.length < 8 || password.length > 50){
                            addLine("Password must contain between 8 and 50 characters.");
                        } else {
                            addLine("confirm password :");
                            waitForUserInput(function(input){
                                confirmedPassword = input;
                                if (confirmedPassword !== password){
                                    addLine("Passwords doesn't match, please try again.");
                                } else {
                                    addLine("Enter email adress (this will only be used to recover your account in case of lost password) :");
                                    waitForUserInput(function(input){
                                        email = input;
                                        var reqEmail = new XMLHttpRequest();
                                        var url = "index.php?p=authentification.checkEmail&email=" + email;
                                        reqEmail.open("GET", url);
                                        reqEmail.addEventListener("load", function () {
                                            if (reqEmail.responseText !== "true"){
                                                addLine("An account with this email already exists.");
                                            } else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                                                var reqRegister = new XMLHttpRequest();
                                                var url = "index.php?p=authentification.register&username=" + username + "&password=" + password + "&email=" + email
                                                reqRegister.open("GET", url);
                                                reqRegister.addEventListener("load", function () {
                                                    if (reqRegister.responseText == "true"){
                                                        addLine("Registration successful. You may login.");
                                                    } else {
                                                        addLine("An error occured. Please retry.");
                                                    }
                                                });
                                                reqRegister.send(null);
                                            } else {
                                                addLine("invalid email. Please enter a valid email.");
                                            }
                                        });
                                        reqEmail.send(null);
                                    })
                                }
                            })
                        }
                    })
                }
            });
            reqUsername.send(null);
        })
    }
});

function waitForUserInput(callback){
    inputWin.removeEventListener('keypress', executeListener, true);
    inputWin.removeEventListener('keydown', historyListener, true);
    inputWin.addEventListener('keypress', function listenForUserInput(e){
        var key = e.which || e.keyCode;
        if (key === 13) { // 13 is enter
            inputWin.removeEventListener('keypress', listenForUserInput, true);
            inputWin.addEventListener('keypress', executeListener, true);
            inputWin.addEventListener('keydown', historyListener, true);
            var input = inputWin.value;
            inputWin.value = "";
            callback(input);
        }
    }, true);
}

intro.action();