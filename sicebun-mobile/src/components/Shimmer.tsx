import React from 'react';

export const ShimmerCard: React.FC = () => {
  return (
    <div className="card shimmer-card" style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
      <div className="shimmer" style={{ width: '100%', height: '140px', borderRadius: '12px' }}></div>
      <div className="shimmer" style={{ width: '60%', height: '18px', borderRadius: '4px' }}></div>
      <div className="shimmer" style={{ width: '40%', height: '12px', borderRadius: '4px' }}></div>
    </div>
  );
};

export const ShimmerListItem: React.FC = () => {
  return (
    <div className="card" style={{ display: 'flex', gap: '16px', alignItems: 'center' }}>
      <div className="shimmer" style={{ width: '60px', height: '60px', borderRadius: '50%', flexShrink: 0 }}></div>
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: '8px' }}>
        <div className="shimmer" style={{ width: '50%', height: '16px', borderRadius: '4px' }}></div>
        <div className="shimmer" style={{ width: '80%', height: '12px', borderRadius: '4px' }}></div>
        <div className="shimmer" style={{ width: '30%', height: '10px', borderRadius: '4px' }}></div>
      </div>
    </div>
  );
};

export const ShimmerDetail: React.FC = () => {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
      <div className="shimmer" style={{ width: '100%', height: '220px', borderRadius: '16px' }}></div>
      <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
        <div className="shimmer" style={{ width: '70%', height: '24px', borderRadius: '4px' }}></div>
        <div className="shimmer" style={{ width: '40%', height: '16px', borderRadius: '4px' }}></div>
      </div>
      <div className="card" style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
        <div className="shimmer" style={{ width: '100%', height: '16px', borderRadius: '4px' }}></div>
        <hr style={{ opacity: 0.05 }} />
        <div className="shimmer" style={{ width: '90%', height: '16px', borderRadius: '4px' }}></div>
        <hr style={{ opacity: 0.05 }} />
        <div className="shimmer" style={{ width: '95%', height: '16px', borderRadius: '4px' }}></div>
      </div>
    </div>
  );
};

export const ShimmerDashboard: React.FC = () => {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
      {/* Top Header info */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <div className="shimmer" style={{ width: '140px', height: '20px', borderRadius: '4px', marginBottom: '8px' }}></div>
          <div className="shimmer" style={{ width: '90px', height: '14px', borderRadius: '4px' }}></div>
        </div>
        <div className="shimmer" style={{ width: '44px', height: '44px', borderRadius: '50%' }}></div>
      </div>

      {/* Hero Stats */}
      <div className="card" style={{ padding: '24px' }}>
        <div className="shimmer" style={{ width: '100px', height: '14px', borderRadius: '4px', marginBottom: '12px' }}></div>
        <div className="shimmer" style={{ width: '60px', height: '36px', borderRadius: '4px' }}></div>
      </div>

      {/* Grid Stats */}
      <div className="grid-2">
        <div className="card" style={{ margin: 0 }}><div className="shimmer" style={{ width: '80%', height: '40px', borderRadius: '8px' }}></div></div>
        <div className="card" style={{ margin: 0 }}><div className="shimmer" style={{ width: '80%', height: '40px', borderRadius: '8px' }}></div></div>
      </div>

      {/* Horiz Swiper Loader */}
      <div>
        <div className="shimmer" style={{ width: '100px', height: '18px', borderRadius: '4px', marginBottom: '12px' }}></div>
        <div style={{ display: 'flex', gap: '12px', overflow: 'hidden' }}>
          <div className="shimmer" style={{ width: '130px', height: '150px', borderRadius: '12px', flexShrink: 0 }}></div>
          <div className="shimmer" style={{ width: '130px', height: '150px', borderRadius: '12px', flexShrink: 0 }}></div>
          <div className="shimmer" style={{ width: '130px', height: '150px', borderRadius: '12px', flexShrink: 0 }}></div>
        </div>
      </div>
    </div>
  );
};
