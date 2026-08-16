from flask import Flask, send_from_directory, request, Response
import subprocess
import os
import tempfile

app = Flask(__name__)
app.config['MAX_CONTENT_LENGTH'] = 100 * 1024 * 1024  # 100MB max file size

@app.route('/')
def index():
    return send_from_directory('..', 'index.html')

@app.route('/<path:filename>', methods=['GET', 'POST'])
def serve_file(filename):
    if filename.endswith('.php'):
        # Execute PHP file
        try:
            # Prepare environment variables
            env = dict(os.environ)
            env.update({
                'REQUEST_METHOD': request.method,
                'QUERY_STRING': request.query_string.decode(),
                'CONTENT_TYPE': request.content_type or '',
                'REQUEST_URI': request.path,
                'SERVER_NAME': request.host.split(':')[0],
                'SERVER_PORT': request.host.split(':')[1] if ':' in request.host else '80',
                'HTTPS': 'on' if request.is_secure else 'off',
                'HTTP_HOST': request.host,
                'HTTP_USER_AGENT': request.headers.get('User-Agent', ''),
                'REMOTE_ADDR': request.remote_addr or '127.0.0.1',
            })
            
            # Handle POST data
            if request.method == 'POST':
                if request.content_type and 'multipart/form-data' in request.content_type:
                    # Handle file uploads
                    with tempfile.NamedTemporaryFile(delete=False) as tmp:
                        tmp.write(request.get_data())
                        tmp.flush()
                        env['CONTENT_LENGTH'] = str(os.path.getsize(tmp.name))
                        
                        # Execute PHP with stdin
                        with open(tmp.name, 'rb') as stdin_file:
                            result = subprocess.run(['php', f'../{filename}'], 
                                                  stdin=stdin_file,
                                                  capture_output=True, 
                                                  cwd='.',
                                                  env=env)
                        os.unlink(tmp.name)
                else:
                    # Handle regular POST data
                    post_data = request.get_data()
                    env['CONTENT_LENGTH'] = str(len(post_data))
                    
                    result = subprocess.run(['php', f'../{filename}'], 
                                          input=post_data,
                                          capture_output=True, 
                                          cwd='.',
                                          env=env)
            else:
                # GET request
                env['CONTENT_LENGTH'] = '0'
                result = subprocess.run(['php', f'../{filename}'], 
                                      capture_output=True, 
                                      text=True,
                                      cwd='.',
                                      env=env)
            
            if result.returncode == 0:
                output = result.stdout if isinstance(result.stdout, str) else result.stdout.decode('utf-8', errors='ignore')
                
                # Check if output contains headers
                if '\n\n' in output or '\r\n\r\n' in output:
                    parts = output.split('\n\n', 1) if '\n\n' in output else output.split('\r\n\r\n', 1)
                    headers_part = parts[0]
                    body_part = parts[1] if len(parts) > 1 else ''
                    
                    # Parse headers
                    response = Response(body_part)
                    for line in headers_part.split('\n'):
                        if ':' in line:
                            key, value = line.split(':', 1)
                            response.headers[key.strip()] = value.strip()
                    
                    return response
                else:
                    return Response(output, mimetype='text/html')
            else:
                error_msg = result.stderr if isinstance(result.stderr, str) else result.stderr.decode('utf-8', errors='ignore')
                return Response(f"PHP Error: {error_msg}", status=500)
                
        except Exception as e:
            return Response(f"Error executing PHP: {str(e)}", status=500)
    else:
        return send_from_directory('..', filename)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
