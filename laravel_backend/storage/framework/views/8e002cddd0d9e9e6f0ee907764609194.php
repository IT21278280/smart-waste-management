<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste API Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .endpoint { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .method { display: inline-block; padding: 3px 8px; border-radius: 3px; color: white; font-weight: bold; }
        .get { background-color: #61affe; }
        .post { background-color: #49cc90; }
        .delete { background-color: #f93e3e; }
        button { background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .response { margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px; white-space: pre-wrap; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Smart Waste Management API Test</h1>
    
    <div class="endpoint">
        <h3><span class="method get">GET</span> /api/health</h3>
        <button onclick="testHealth()">Test Health Check</button>
        <div id="health-response" class="response" style="display:none;"></div>
    </div>

    <div class="endpoint">
        <h3><span class="method post">POST</span> /api/register</h3>
        <input type="text" id="reg-name" placeholder="Name" value="Test User">
        <input type="email" id="reg-email" placeholder="Email" value="test@example.com">
        <input type="password" id="reg-password" placeholder="Password" value="password123">
        <input type="password" id="reg-password-confirm" placeholder="Confirm Password" value="password123">
        <button onclick="testRegister()">Test Registration</button>
        <div id="register-response" class="response" style="display:none;"></div>
    </div>

    <div class="endpoint">
        <h3><span class="method post">POST</span> /api/login</h3>
        <input type="email" id="login-email" placeholder="Email" value="test@example.com">
        <input type="password" id="login-password" placeholder="Password" value="password123">
        <button onclick="testLogin()">Test Login</button>
        <div id="login-response" class="response" style="display:none;"></div>
    </div>

    <div class="endpoint">
        <h3><span class="method get">GET</span> /api/user</h3>
        <p>Requires authentication token from login</p>
        <button onclick="testUser()">Test Get User</button>
        <div id="user-response" class="response" style="display:none;"></div>
    </div>

    <div class="endpoint">
        <h3><span class="method post">POST</span> /api/logout</h3>
        <p>Requires authentication token from login</p>
        <button onclick="testLogout()">Test Logout</button>
        <div id="logout-response" class="response" style="display:none;"></div>
    </div>

    <script>
        let authToken = '';

        async function makeRequest(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            };

            if (authToken) {
                options.headers['Authorization'] = `Bearer ${authToken}`;
            }

            if (data) {
                options.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(url, options);
                const result = await response.json();
                return { status: response.status, data: result };
            } catch (error) {
                return { status: 0, data: { error: error.message } };
            }
        }

        async function testHealth() {
            const result = await makeRequest('/api/health');
            document.getElementById('health-response').style.display = 'block';
            document.getElementById('health-response').textContent = JSON.stringify(result, null, 2);
        }

        async function testRegister() {
            const data = {
                name: document.getElementById('reg-name').value,
                email: document.getElementById('reg-email').value,
                password: document.getElementById('reg-password').value,
                password_confirmation: document.getElementById('reg-password-confirm').value
            };
            
            const result = await makeRequest('/api/register', 'POST', data);
            document.getElementById('register-response').style.display = 'block';
            document.getElementById('register-response').textContent = JSON.stringify(result, null, 2);
            
            if (result.data.data && result.data.data.access_token) {
                authToken = result.data.data.access_token;
                alert('Registration successful! Token saved for other requests.');
            }
        }

        async function testLogin() {
            const data = {
                email: document.getElementById('login-email').value,
                password: document.getElementById('login-password').value
            };
            
            const result = await makeRequest('/api/login', 'POST', data);
            document.getElementById('login-response').style.display = 'block';
            document.getElementById('login-response').textContent = JSON.stringify(result, null, 2);
            
            if (result.data.data && result.data.data.access_token) {
                authToken = result.data.data.access_token;
                alert('Login successful! Token saved for other requests.');
            }
        }

        async function testUser() {
            if (!authToken) {
                alert('Please login first to get an authentication token.');
                return;
            }
            
            const result = await makeRequest('/api/user');
            document.getElementById('user-response').style.display = 'block';
            document.getElementById('user-response').textContent = JSON.stringify(result, null, 2);
        }

        async function testLogout() {
            if (!authToken) {
                alert('Please login first to get an authentication token.');
                return;
            }
            
            const result = await makeRequest('/api/logout', 'POST');
            document.getElementById('logout-response').style.display = 'block';
            document.getElementById('logout-response').textContent = JSON.stringify(result, null, 2);
            
            if (result.status === 200) {
                authToken = '';
                alert('Logout successful! Token cleared.');
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\Smart Waste App\laravel_backend\resources\views/api-test.blade.php ENDPATH**/ ?>