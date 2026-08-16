from flask import Flask, send_from_directory, request, Response
import subprocess
import os

app = Flask(__name__)

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/<path:filename>')
def serve_file(filename):
    if filename.endswith('.php'):
        # Execute PHP file
        try:
            result = subprocess.run(['php', filename], 
                                  capture_output=True, 
                                  text=True, 
                                  cwd='.',
                                  env=dict(os.environ, **{
                                      'REQUEST_METHOD': request.method,
                                      'QUERY_STRING': request.query_string.decode(),
                                      'CONTENT_TYPE': request.content_type or '',
                                      'CONTENT_LENGTH': str(len(request.get_data())),
                                  }))
            
            if result.returncode == 0:
                return Response(result.stdout, mimetype='text/html')
            else:
                return Response(f"PHP Error: {result.stderr}", status=500)
        except Exception as e:
            return Response(f"Error executing PHP: {str(e)}", status=500)
    else:
        return send_from_directory('.', filename)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
