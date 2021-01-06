const Register = function () { };

Register.prototype.attempt = async function (name, email, password) {
    const credentials = {};
    credentials.name = name;
    credentials.email = email;
    credentials.password = password;

    const body = {};
    body.method = "POST";
    body.body = JSON.stringify(credentials);

    const request = await fetch('/register?api', body)

    const response = await request.json();

    return response;
}






const Login = function () { };

Login.prototype.attempt = async function (email, password) {
    const credentials = {};
    credentials.email = email;
    credentials.password = password;

    const body = {};
    body.method = "POST";
    body.body = JSON.stringify(credentials);

    const request = await fetch('/login?api', body)

    const response = await request.json();

    return response;
}





const login = new Login();
const register = new Register();