import { useEffect, useMemo, useState } from 'react';

const API_BASE_URL = (import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api')
  .replace(/\/+$/, '');

async function request(path, options = {}) {
  const token = sessionStorage.getItem('product_access_token');
  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers: {
      Accept: 'application/json',
      ...(options.body ? { 'Content-Type': 'application/json' } : {}),
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  });

  const result = await response.json().catch(() => ({}));
  if (!response.ok) {
    const error = new Error(result.error || 'The request could not be completed.');
    error.status = response.status;
    throw error;
  }
  return result;
}

const emptyProduct = {
  product_name: '',
  description: '',
  price: '',
  quantity: '',
};

function App() {
  const [token, setToken] = useState(() => sessionStorage.getItem('product_access_token'));
  const [user, setUser] = useState(() => {
    try {
      return JSON.parse(sessionStorage.getItem('product_user') || 'null');
    } catch {
      return null;
    }
  });
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [notice, setNotice] = useState('');
  const [search, setSearch] = useState('');
  const [dialog, setDialog] = useState(null);

  const filteredProducts = useMemo(() => {
    const query = search.trim().toLowerCase();
    if (!query) return products;
    return products.filter((product) =>
      `${product.product_name} ${product.description}`.toLowerCase().includes(query),
    );
  }, [products, search]);

  const loadProducts = async () => {
    setLoading(true);
    setError('');
    try {
      const result = await request('/products');
      setProducts(result.data || []);
    } catch (loadError) {
      if (loadError.status === 401) logout();
      else setError(loadError.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (token) loadProducts();
  }, [token]);

  async function handleLogin(event) {
    event.preventDefault();
    setError('');
    const form = new FormData(event.currentTarget);
    try {
      const result = await request('/login', {
        method: 'POST',
        body: JSON.stringify({
          email: form.get('email'),
          password: form.get('password'),
        }),
      });
      sessionStorage.setItem('product_access_token', result.access_token);
      sessionStorage.setItem('product_user', JSON.stringify(result.user));
      setToken(result.access_token);
      setUser(result.user);
    } catch (loginError) {
      setError(loginError.message);
    }
  }

  function logout() {
    sessionStorage.removeItem('product_access_token');
    sessionStorage.removeItem('product_user');
    setToken(null);
    setUser(null);
    setProducts([]);
    setDialog(null);
  }

  async function saveProduct(event) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    const product = {
      product_name: form.get('product_name'),
      description: form.get('description'),
      price: form.get('price'),
      quantity: form.get('quantity'),
    };
    const editing = dialog?.product;
    setError('');

    try {
      await request(editing ? `/products/${editing.id}` : '/products', {
        method: editing ? 'PUT' : 'POST',
        body: JSON.stringify(product),
      });
      setDialog(null);
      setNotice(editing ? 'Product updated successfully.' : 'Product added successfully.');
      await loadProducts();
    } catch (saveError) {
      if (saveError.status === 401) logout();
      else setError(saveError.message);
    }
  }

  async function deleteProduct(product) {
    if (!window.confirm(`Delete "${product.product_name}"? This cannot be undone.`)) return;
    setError('');
    try {
      await request(`/products/${product.id}`, { method: 'DELETE' });
      setNotice('Product deleted successfully.');
      await loadProducts();
    } catch (deleteError) {
      if (deleteError.status === 401) logout();
      else setError(deleteError.message);
    }
  }

  useEffect(() => {
    if (!notice) return undefined;
    const timeout = window.setTimeout(() => setNotice(''), 3500);
    return () => window.clearTimeout(timeout);
  }, [notice]);

  if (!token || !user) {
    return (
      <main className="login-screen">
        <section className="login-card">
          <div className="brand-mark">P</div>
          <p className="eyebrow">PRODUCT DESK</p>
          <h1>Welcome back</h1>
          <p className="muted">Sign in to manage your inventory.</p>
          {error && <div className="alert error" role="alert">{error}</div>}
          <form onSubmit={handleLogin} className="login-form">
            <label htmlFor="email">Email address</label>
            <input id="email" name="email" type="email" autoComplete="username" placeholder="you@company.com" required />
            <label htmlFor="password">Password</label>
            <input id="password" name="password" type="password" autoComplete="current-password" placeholder="Enter your password" required />
            <button className="button primary full-width" type="submit">Sign in <span aria-hidden="true">→</span></button>
          </form>
          <p className="login-footnote">Your inventory, all in one place.</p>
        </section>
      </main>
    );
  }

  const inventoryValue = products.reduce(
    (total, product) => total + Number(product.price) * Number(product.quantity),
    0,
  );
  const stockCount = products.reduce((total, product) => total + Number(product.quantity), 0);

  return (
    <main className="app-shell">
      <aside className="sidebar">
        <a className="brand" href="/" aria-label="Product Desk home">
          <span className="brand-mark small">P</span>
          <span>Product<span className="brand-light">Desk</span></span>
        </a>
        <p className="nav-label">WORKSPACE</p>
        <div className="nav-item active"><span className="nav-icon">▦</span> Inventory</div>
        <div className="sidebar-bottom">
          <div className="user-chip">
            <div className="avatar">{(user.username || user.email || 'U').charAt(0).toUpperCase()}</div>
            <div className="user-details">
              <strong>{user.username || user.email}</strong>
              <span>{user.role || 'Account'}</span>
            </div>
          </div>
          <button className="logout-button" type="button" onClick={logout}>↗ <span>Log out</span></button>
        </div>
      </aside>

      <section className="workspace">
        <header className="topbar">
          <div className="breadcrumb">Workspace <span>/</span> <strong>Inventory</strong></div>
          <div className="topbar-date">{new Intl.DateTimeFormat(undefined, { dateStyle: 'medium' }).format(new Date())}</div>
        </header>

        <div className="content">
          <div className="page-heading">
            <div>
              <p className="eyebrow">OVERVIEW</p>
              <h1>Product inventory</h1>
              <p className="muted">Keep track of your products and stock levels.</p>
            </div>
            <button className="button primary" type="button" onClick={() => { setError(''); setDialog({ product: null }); }}>
              <span aria-hidden="true">＋</span> Add product
            </button>
          </div>

          {error && <div className="alert error" role="alert">{error}</div>}
          {notice && <div className="alert success" role="status">{notice}</div>}

          <div className="stats-grid">
            <article className="stat-card">
              <div className="stat-icon lavender">▦</div>
              <div><p>Total products</p><strong>{products.length}</strong></div>
              <span className="stat-caption">in your catalog</span>
            </article>
            <article className="stat-card">
              <div className="stat-icon mint">⌑</div>
              <div><p>Units in stock</p><strong>{stockCount.toLocaleString()}</strong></div>
              <span className="stat-caption">across all products</span>
            </article>
            <article className="stat-card">
              <div className="stat-icon peach">$</div>
              <div><p>Inventory value</p><strong>{new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD', maximumFractionDigits: 2 }).format(inventoryValue)}</strong></div>
              <span className="stat-caption">based on current stock</span>
            </article>
          </div>

          <section className="panel">
            <div className="panel-heading">
              <div>
                <h2>All products <span className="count-pill">{products.length}</span></h2>
                <p className="muted">View and manage your product catalog.</p>
              </div>
              <label className="search-box">
                <span aria-hidden="true">⌕</span>
                <input type="search" value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Search products..." aria-label="Search products" />
              </label>
            </div>

            <div className="table-scroll">
              <table>
                <thead>
                  <tr><th>PRODUCT</th><th>PRICE</th><th>STOCK</th><th>ADDED</th><th><span className="visually-hidden">Actions</span></th></tr>
                </thead>
                <tbody>
                  {loading ? (
                    <tr><td colSpan="5" className="empty-state">Loading products…</td></tr>
                  ) : filteredProducts.length === 0 ? (
                    <tr>
                      <td colSpan="5" className="empty-state">
                        <div className="empty-icon">▦</div>
                        <strong>{search ? 'No matching products' : 'Your catalog is empty'}</strong>
                        <span>{search ? 'Try another search term.' : 'Add your first product to get started.'}</span>
                        {!search && <button className="text-button" type="button" onClick={() => setDialog({ product: null })}>Add a product →</button>}
                      </td>
                    </tr>
                  ) : filteredProducts.map((product) => (
                    <tr key={product.id}>
                      <td>
                        <div className="product-cell">
                          <div className="product-avatar">{product.product_name.charAt(0).toUpperCase()}</div>
                          <div><strong>{product.product_name}</strong><span>{product.description || 'No description'}</span></div>
                        </div>
                      </td>
                      <td className="price-cell">{new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(Number(product.price))}</td>
                      <td><span className={`stock-pill ${Number(product.quantity) === 0 ? 'out' : Number(product.quantity) < 10 ? 'low' : 'in'}`}>
                        <span className="stock-dot" />{Number(product.quantity) === 0 ? 'Out of stock' : `${product.quantity} in stock`}
                      </span></td>
                      <td className="date-cell">{product.created_at ? new Date(product.created_at.replace(' ', 'T')).toLocaleDateString() : '—'}</td>
                      <td>
                        <div className="row-actions">
                          <button type="button" className="icon-button" aria-label={`Edit ${product.product_name}`} title="Edit" onClick={() => { setError(''); setDialog({ product }); }}>✎</button>
                          <button type="button" className="icon-button danger" aria-label={`Delete ${product.product_name}`} title="Delete" onClick={() => deleteProduct(product)}>⌫</button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <div className="panel-footer">
              <span>Showing {filteredProducts.length} of {products.length} products</span>
              <button className="refresh-button" type="button" onClick={loadProducts} disabled={loading}>↻ Refresh</button>
            </div>
          </section>
        </div>
      </section>

      {dialog && (
        <div className="modal-backdrop" role="presentation" onMouseDown={(event) => {
          if (event.target === event.currentTarget) setDialog(null);
        }}>
          <section className="modal" role="dialog" aria-modal="true" aria-labelledby="product-form-title">
            <div className="modal-heading">
              <div><p className="eyebrow">{dialog.product ? 'UPDATE CATALOG' : 'NEW CATALOG ITEM'}</p><h2 id="product-form-title">{dialog.product ? 'Edit product' : 'Add a product'}</h2></div>
              <button className="close-button" type="button" aria-label="Close" onClick={() => setDialog(null)}>×</button>
            </div>
            {error && <div className="alert error" role="alert">{error}</div>}
            <form className="product-form" onSubmit={saveProduct}>
              <label htmlFor="product_name">Product name</label>
              <input id="product_name" name="product_name" maxLength="100" defaultValue={dialog.product?.product_name || ''} placeholder="e.g. Ceramic coffee mug" required />
              <label htmlFor="description">Description <span className="optional">(optional)</span></label>
              <textarea id="description" name="description" rows="3" defaultValue={dialog.product?.description || ''} placeholder="A short description of your product" />
              <div className="form-row">
                <div><label htmlFor="price">Price</label><div className="input-prefix"><span>$</span><input id="price" name="price" type="number" min="0" max="99999999.99" step="0.01" defaultValue={dialog.product?.price || ''} placeholder="0.00" required /></div></div>
                <div><label htmlFor="quantity">Quantity</label><input id="quantity" name="quantity" type="number" min="0" step="1" defaultValue={dialog.product?.quantity ?? ''} placeholder="0" required /></div>
              </div>
              <div className="modal-actions">
                <button className="button secondary" type="button" onClick={() => setDialog(null)}>Cancel</button>
                <button className="button primary" type="submit">{dialog.product ? 'Save changes' : 'Add product'}</button>
              </div>
            </form>
          </section>
        </div>
      )}
    </main>
  );
}

export default App;
