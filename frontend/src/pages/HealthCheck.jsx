import React, { useState, useEffect } from 'react';
import { ShieldCheck, Server, Database, RefreshCw, ChevronRight } from 'lucide-react';

export default function HealthCheck() {
  const [backendStatus, setBackendStatus] = useState('loading');
  const [dbStatus, setDbStatus] = useState('loading');
  const [dbDetails, setDbDetails] = useState('');
  const [timestamp, setTimestamp] = useState('');
  const [isRefreshing, setIsRefreshing] = useState(false);

  const fetchHealth = async () => {
    setIsRefreshing(true);
    try {
      const baseUrl = import.meta.env.VITE_API_URL || (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' ? 'http://localhost:5001/api' : '/api');
      const res = await fetch(`${baseUrl}/health`);
      if (!res.ok) throw new Error('API server returned error status');
      const data = await res.json();
      
      setBackendStatus('ok');
      setDbStatus(data.database.connected ? 'connected' : 'disconnected');
      setDbDetails(data.database.details);
      setTimestamp(data.timestamp || new Date().toISOString());
    } catch (err) {
      setBackendStatus('failed');
      setDbStatus('failed');
      setDbDetails(err.message || 'Could not reach backend API server.');
    } finally {
      setIsRefreshing(false);
    }
  };

  useEffect(() => {
    fetchHealth();
  }, []);

  return (
    <div style={{
      maxWidth: '800px',
      margin: '4rem auto',
      padding: '2.5rem',
      backgroundColor: '#ffffff',
      borderRadius: '16px',
      boxShadow: '0 10px 25px -5px rgba(15, 23, 42, 0.08)',
      border: '1px solid #E2E8F0',
      fontFamily: 'var(--font-secondary)'
    }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <div>
          <h1 style={{ fontSize: '2rem', color: '#0F172A', fontFamily: 'var(--font-primary)' }}>Zuvio Phase 1 Health Check</h1>
          <p style={{ color: '#64748B', fontSize: '0.95rem', marginTop: '0.25rem' }}>System diagnostics and microservice connectivity state</p>
        </div>
        <button 
          onClick={fetchHealth} 
          disabled={isRefreshing}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.5rem',
            padding: '0.6rem 1.2rem',
            backgroundColor: '#0F172A',
            color: '#FFFFFF',
            border: 'none',
            borderRadius: '6px',
            fontSize: '0.9rem',
            fontWeight: '500',
            cursor: 'pointer',
            opacity: isRefreshing ? 0.7 : 1,
            transition: 'background-color 0.2s'
          }}
        >
          <RefreshCw size={16} className={isRefreshing ? 'spin-anim' : ''} />
          {isRefreshing ? 'Checking...' : 'Refresh'}
        </button>
      </div>

      <style>{`
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        .spin-anim {
          animation: spin 1s linear infinite;
        }
        .status-card {
          padding: 1.5rem;
          border-radius: 12px;
          border: 1px solid #E2E8F0;
          display: flex;
          align-items: center;
          gap: 1.25rem;
          background: #F8FAFC;
          transition: all 0.2s ease;
        }
        .status-card:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
      `}</style>

      <div style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
        
        {/* Frontend Status */}
        <div className="status-card" style={{ borderLeft: '5px solid #059669' }}>
          <div style={{ padding: '0.75rem', backgroundColor: '#ECFDF5', borderRadius: '50%', color: '#059669' }}>
            <ShieldCheck size={28} />
          </div>
          <div style={{ flexGrow: 1 }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <h3 style={{ fontSize: '1.1rem', margin: 0 }}>Frontend Server</h3>
              <span style={{ fontSize: '0.85rem', fontWeight: 'bold', color: '#059669', backgroundColor: '#D1FAE5', padding: '0.2rem 0.6rem', borderRadius: '20px' }}>OK</span>
            </div>
            <p style={{ color: '#64748B', fontSize: '0.85rem', marginTop: '0.25rem' }}>React app is running locally via Vite.</p>
          </div>
        </div>

        {/* Backend API Status */}
        <div className="status-card" style={{ 
          borderLeft: `5px solid ${backendStatus === 'ok' ? '#059669' : backendStatus === 'failed' ? '#EF4444' : '#D97706'}` 
        }}>
          <div style={{ 
            padding: '0.75rem', 
            backgroundColor: backendStatus === 'ok' ? '#ECFDF5' : backendStatus === 'failed' ? '#FEF2F2' : '#FFFBEB', 
            borderRadius: '50%', 
            color: backendStatus === 'ok' ? '#059669' : backendStatus === 'failed' ? '#EF4444' : '#D97706' 
          }}>
            <Server size={28} />
          </div>
          <div style={{ flexGrow: 1 }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <h3 style={{ fontSize: '1.1rem', margin: 0 }}>Backend Express API</h3>
              <span style={{ 
                fontSize: '0.85rem', 
                fontWeight: 'bold', 
                color: backendStatus === 'ok' ? '#059669' : backendStatus === 'failed' ? '#EF4444' : '#B45309', 
                backgroundColor: backendStatus === 'ok' ? '#D1FAE5' : backendStatus === 'failed' ? '#FEE2E2' : '#FEF3C7', 
                padding: '0.2rem 0.6rem', 
                borderRadius: '20px' 
              }}>
                {backendStatus === 'ok' ? 'OK' : backendStatus === 'failed' ? 'FAILED' : 'CONNECTING...'}
              </span>
            </div>
            <p style={{ color: '#64748B', fontSize: '0.85rem', marginTop: '0.25rem' }}>
              {backendStatus === 'ok' ? 'Express web application endpoints operational.' : 'Please ensure backend service is running on port 5001.'}
            </p>
          </div>
        </div>

        {/* Local MySQL Database Connection */}
        <div className="status-card" style={{ 
          borderLeft: `5px solid ${dbStatus === 'connected' ? '#059669' : dbStatus === 'disconnected' ? '#D97706' : dbStatus === 'failed' ? '#EF4444' : '#D97706'}` 
        }}>
          <div style={{ 
            padding: '0.75rem', 
            backgroundColor: dbStatus === 'connected' ? '#ECFDF5' : dbStatus === 'disconnected' ? '#FFFBEB' : '#FEF2F2', 
            borderRadius: '50%', 
            color: dbStatus === 'connected' ? '#059669' : dbStatus === 'disconnected' ? '#D97706' : '#EF4444' 
          }}>
            <Database size={28} />
          </div>
          <div style={{ flexGrow: 1 }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <h3 style={{ fontSize: '1.1rem', margin: 0 }}>MySQL Connection</h3>
              <span style={{ 
                fontSize: '0.85rem', 
                fontWeight: 'bold', 
                color: dbStatus === 'connected' ? '#059669' : '#EF4444', 
                backgroundColor: dbStatus === 'connected' ? '#D1FAE5' : '#FEE2E2', 
                padding: '0.2rem 0.6rem', 
                borderRadius: '20px' 
              }}>
                {dbStatus === 'connected' ? 'CONNECTED' : 'Database connection required'}
              </span>
            </div>
            <p style={{ color: '#64748B', fontSize: '0.85rem', marginTop: '0.25rem' }}>
              {dbStatus === 'connected' ? 'Database connection active.' : 'Database offline. CMS mutations and form entries are disabled.'}
            </p>
          </div>
        </div>
      </div>

      <div style={{ borderTop: '1px solid #E2E8F0', marginTop: '2.5rem', paddingTop: '1.5rem', display: 'flex', justifyContent: 'space-between', fontSize: '0.8rem', color: '#64748B' }}>
        <span>Timestamp: {timestamp || 'N/A'}</span>
        <span>Environment: Development</span>
      </div>
    </div>
  );
}
