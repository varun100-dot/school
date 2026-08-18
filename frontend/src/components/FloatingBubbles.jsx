import React from 'react';

export default function FloatingBubbles() {
  const bubbles = [
    { top: '12%', left: '6%', size: '60px', color: 'rgba(10, 137, 152, 0.12)', delay: '0s', duration: '12s' },
    { top: '38%', left: '88%', size: '90px', color: 'rgba(217, 164, 65, 0.10)', delay: '2s', duration: '16s' },
    { top: '75%', left: '12%', size: '75px', color: 'rgba(6, 43, 99, 0.08)', delay: '1s', duration: '14s' },
    { top: '22%', left: '72%', size: '50px', color: 'rgba(10, 137, 152, 0.10)', delay: '3s', duration: '15s' },
    { top: '82%', left: '82%', size: '80px', color: 'rgba(217, 164, 65, 0.12)', delay: '0s', duration: '13s' },
    { top: '55%', left: '5%', size: '100px', color: 'rgba(6, 43, 99, 0.06)', delay: '4s', duration: '18s' }
  ];

  return (
    <div style={{
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      overflow: 'hidden',
      pointerEvents: 'none',
      zIndex: 0
    }}>
      {bubbles.map((b, idx) => (
        <div 
          key={idx} 
          className="floating-bubble"
          style={{
            position: 'absolute',
            top: b.top,
            left: b.left,
            width: b.size,
            height: b.size,
            borderRadius: '50%',
            backgroundColor: b.color,
            filter: 'blur(8px)',
            animationName: 'bubbleFloat',
            animationDuration: b.duration,
            animationDelay: b.delay,
            animationIterationCount: 'infinite',
            animationTimingFunction: 'ease-in-out'
          }}
        />
      ))}
      <style>{`
        @keyframes bubbleFloat {
          0% { transform: translateY(0) translateX(0); }
          50% { transform: translateY(-12px) translateX(8px); }
          100% { transform: translateY(0) translateX(0); }
        }
        @media (prefers-reduced-motion: reduce) {
          .floating-bubble {
            animation: none !important;
          }
        }
      `}</style>
    </div>
  );
}
