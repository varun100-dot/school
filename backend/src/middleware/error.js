// Centralized Express Error Handling Middleware

function errorHandler(err, req, res, next) {
  console.error(`[API Error] ${req.method} ${req.url} : ${err.message}`);
  
  const statusCode = res.statusCode === 200 ? 500 : res.statusCode;
  
  res.status(statusCode).json({
    error: err.message || 'Internal Server Error',
    stack: process.env.NODE_ENV === 'production' ? '🥞' : err.stack
  });
}

module.exports = errorHandler;
