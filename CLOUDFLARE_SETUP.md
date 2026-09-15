# Cloudflare Security Configuration for Titik Simpan

## Overview
This guide configures Cloudflare as a security layer in front of your Vercel deployment to protect against:
- DDoS attacks
- Malicious bots and scrapers
- SQL injection, XSS, and other OWASP Top 10 attacks
- Gambling/phishing redirects
- Card testing and credential stuffing

---

## 1. DNS Configuration

### Add Domain to Cloudflare
1. Go to **dash.cloudflare.com** → **Add Site**
2. Enter your domain (e.g., `titik-simpan.com`)
3. Select **Free** plan
4. Update nameservers at your registrar to Cloudflare's

### DNS Records
| Type | Name | Target | Proxy Status |
|------|------|--------|--------------|
| CNAME | @ | `cname.vercel-dns.com` | **Proxied (Orange Cloud)** |
| CNAME | www | `cname.vercel-dns.com` | **Proxied (Orange Cloud)** |

> **Important**: Use **Proxied** (orange cloud) for all records to enable Cloudflare WAF, DDoS protection, and caching.

---

## 2. SSL/TLS Settings

### Encryption Mode
- **SSL/TLS** → **Overview** → **Full (Strict)**
- This requires valid SSL on Vercel (automatic with Vercel)

### Edge Certificates
- **Always Use HTTPS**: **On**
- **Minimum TLS Version**: **TLS 1.2**
- **Automatic HTTPS Rewrites**: **On**

---

## 3. Security Settings

### WAF (Web Application Firewall)
**Security** → **WAF** → **Managed Rules**

Enable these rule sets:
- ✅ **Cloudflare Managed Ruleset** (OWASP Top 10, PHP, WordPress, etc.)
- ✅ **Cloudflare OWASP Managed Ruleset** (SQLi, XSS, RCE, etc.)

### Custom WAF Rules
Create these custom rules (**Security** → **WAF** → **Custom Rules**):

#### Rule 1: Block Known Bad User Agents
```
Field: http.user_agent
Operator: contains
Value: (sqlmap|nikto|nmap|masscan|zap|burp|hydra|w3af|acunetix|nessus|openvas|dirb|gobuster|feroxbuster|ffuf|wfuzz|sqlninja|sqlsus|bbqsql|nosqlmap|nosqlmap|nosqlmap)
Action: Block
```

#### Rule 2: Block Suspicious Paths (Admin Scanners)
```
Field: http.request.uri.path
Operator: matches regex
Value: ^/(wp-admin|wp-login|phpmyadmin|admin|administrator|manager|phpinfo|server-status|\.git|\.env|backup|dump|sql|\.bak|\.sql$)
Action: Block
```

#### Rule 3: Rate Limit Login/Register
```
Field: http.request.uri.path
Operator: in
Value: ["/login", "/register", "/auth/google/callback", "/profile"]
AND
Field: http.request.method
Operator: eq
Value: "POST"
Action: Managed Challenge (or Rate Limit: 10 req/min per IP)
```

#### Rule 4: Block Tor/VPN/Proxy (Optional - may block legitimate users)
```
Field: ip.geoip.is_tor
Operator: eq
Value: true
Action: Managed Challenge
```

#### Rule 5: Block Countries (Optional - adjust to your needs)
```
Field: ip.geoip.country
Operator: in
Value: ["KP", "IR", "SY", "CU", "RU", "CN"]  # Adjust as needed
Action: Managed Challenge
```

---

## 4. Bot Management

**Security** → **Bots** → **Bot Fight Mode**: **On** (Free tier)
- Challenges suspicious automated traffic
- Reduces credential stuffing and scraping

**Security** → **Bots** → **Super Bot Fight Mode**: Enable if on Pro plan ($20/mo)

---

## 5. Rate Limiting (Free Tier: 1 Rule)

**Security** → **Rate Limiting** → **Create Rate Limiting Rule**

```
Rule Name: API & Auth Rate Limit
URL Pattern: yourdomain.com/*
Technique: Simulate (test first), then Block
Threshold: 100 requests per 1 minute
Action: Block for 15 minutes
Bypass: Known bots (Google, Bing, etc.)
```

---

## 6. Page Rules (Free Tier: 3 Rules)

### Rule 1: Cache Static Assets
```
URL: yourdomain.com/assets/*
Settings:
- Cache Level: Cache Everything
- Edge Cache TTL: 1 year
- Browser Cache TTL: 1 year
```

### Rule 2: Bypass Cache for API/Admin
```
URL: yourdomain.com/api/*
URL: yourdomain.com/*/edit*
URL: yourdomain.com/*/create*
Settings:
- Cache Level: Bypass
- Disable Performance
```

### Rule 3: Force HTTPS
```
URL: http://yourdomain.com/*
Setting: Always Use HTTPS
```

---

## 7. Transform Rules (Optional)

**Rules** → **Transform Rules** → **Modify Response Header**

Add security headers at edge (defense in depth):
```
Rule Name: Security Headers
When: All incoming requests
Then: Modify Response Header
- Set: Strict-Transport-Security = "max-age=31536000; includeSubDomains; preload"
- Set: X-Content-Type-Options = "nosniff"
- Set: X-Frame-Options = "DENY"
- Set: Referrer-Policy = "strict-origin-when-cross-origin"
- Set: Permissions-Policy = "camera=(), microphone=(), geolocation=(), payment=()"
```

---

## 8. Workers (Advanced - Free Tier: 100k req/day)

Create a Worker for additional protection:

```javascript
// Security Worker - blocks suspicious requests at edge
export default {
  async fetch(request, env, ctx) {
    const url = new URL(request.url);
    const ua = request.headers.get('User-Agent') || '';
    
    // Block obvious scanners
    const badAgents = /sqlmap|nikto|nmap|masscan|zap|burp|hydra|w3af|acunetix|nessus|openvas|dirb|gobuster|feroxbuster|ffuf|wfuzz/i;
    if (badAgents.test(ua)) {
      return new Response('Forbidden', { status: 403 });
    }
    
    // Block suspicious paths
    const badPaths = /\.(git|env|bak|sql|dump|backup|log)$/i;
    if (badPaths.test(url.pathname)) {
      return new Response('Not Found', { status: 404 });
    }
    
    // Add security headers
    const response = await fetch(request);
    const newHeaders = new Headers(response.headers);
    newHeaders.set('X-Content-Type-Options', 'nosniff');
    newHeaders.set('X-Frame-Options', 'DENY');
    newHeaders.set('Referrer-Policy', 'strict-origin-when-cross-origin');
    newHeaders.set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
    
    return new Response(response.body, {
      status: response.status,
      statusText: response.statusText,
      headers: newHeaders
    });
  }
}
```

---

## 9. Vercel Integration

### Connect Vercel to Cloudflare
1. In Vercel: **Settings** → **Domains** → Add your custom domain
2. Vercel will provide CNAME target (`cname.vercel-dns.com`)
3. Add this CNAME in Cloudflare DNS (Proxied)
4. Vercel will provision SSL automatically

### Environment Variables (Vercel Dashboard)
Ensure these are set in Vercel:
```
APP_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
DB_URL=postgresql://... (Neon direct host)
```

---

## 10. Monitoring & Alerts

### Analytics
- **Analytics** → **Web Analytics** → Enable (free, privacy-first)
- **Analytics** → **Security Events** → Monitor blocked requests

### Alerts (Free: 1 Alert)
**Notifications** → **Create Notification**
- **Type**: WAF Events
- **Condition**: > 100 blocked requests in 5 minutes
- **Action**: Email / Webhook

---

## 11. Testing Your Setup

### Verify Security Headers
```bash
curl -I https://yourdomain.com/
```
Expected headers:
```
strict-transport-security: max-age=31536000; includeSubDomains; preload
x-content-type-options: nosniff
x-frame-options: DENY
referrer-policy: strict-origin-when-cross-origin
permissions-policy: camera=(), microphone=(), geolocation=(), payment=()
content-security-policy: default-src 'self' ...
cf-cache-status: HIT (for cached assets)
cf-ray: xxxxxxxx (Cloudflare Ray ID)
```

### Test WAF
```bash
# Should be blocked
curl "https://yourdomain.com/?id=1' OR '1'='1"
curl "https://yourdomain.com/wp-admin"
curl -A "sqlmap" "https://yourdomain.com/"
```

### Test Rate Limit
```bash
for i in {1..15}; do curl -X POST https://yourdomain.com/login; done
# Should get 429 or challenge after 10 requests
```

---

## 12. Emergency Response

### Under Attack Mode
If you detect an active attack:
1. **Security** → **Overview** → **Under Attack Mode**: **On**
2. This presents a JS challenge to all visitors for 5 seconds
3. Legitimate users pass through; bots are blocked

### Block Specific IP
**Security** → **WAF** → **Tools** → **IP Access Rules**
- Add malicious IPs to **Block** list

---

## 13. Cost Summary (Free Tier)

| Feature | Free Tier Limit |
|---------|----------------|
| DNS | Unlimited |
| CDN | Unlimited bandwidth |
| WAF Managed Rules | 1 ruleset (Cloudflare + OWASP) |
| Custom WAF Rules | 5 rules |
| Rate Limiting | 1 rule |
| Page Rules | 3 rules |
| Bot Fight Mode | Basic |
| Workers | 100,000 requests/day |
| Analytics | Free |
| SSL | Free (Universal SSL) |

**Total Cost: $0/month** for most use cases.

---

## 14. Verification Checklist

After deployment, verify:

- [ ] Domain resolves via Cloudflare (check `cf-ray` header)
- [ ] SSL Labs grade A+ (test at ssllabs.com/ssltest)
- [ ] Security headers present (use securityheaders.com)
- [ ] CSP blocks inline scripts without nonce (test in browser console)
- [ ] WAF blocks SQLi payloads (test with `?id=1' OR '1'='1`)
- [ ] Rate limiting works on login endpoint
- [ ] Static assets cached (check `cf-cache-status: HIT`)
- [ ] Mobile redirect to gambling sites stopped
- [ ] Google OAuth callback works
- [ ] PDF export works (iframe allowed for reports.previewFile)

---

## 15. Troubleshooting

### Issue: Google OAuth Redirect Mismatch
- Ensure `GOOGLE_REDIRECT_URI` in Vercel matches exactly: `https://yourdomain.com/auth/google/callback`
- Update in Google Cloud Console: Authorized redirect URIs

### Issue: PDF Preview Not Loading
- CSP `frame-src` must include your domain
- Check `SecurityHeaders` middleware allows `frame-ancestors` for `reports.previewFile`

### Issue: Assets Not Loading
- Verify DNS CNAME points to `cname.vercel-dns.com`
- Check Cloudflare cache isn't serving stale content (purge cache if needed)

### Issue: Mixed Content Warnings
- Ensure `APP_URL` in Vercel uses `https://`
- Cloudflare SSL mode: **Full (Strict)**
- `SESSION_SECURE_COOKIE=true` in Vercel env

---

## Quick Reference: Key Files Modified

| File | Purpose |
|------|---------|
| `app/Http/Middleware/SecurityHeaders.php` | Comprehensive security headers + CSP |
| `app/Http/Middleware/SetLocale.php` | Generates CSP nonce per request |
| `resources/views/layouts/app.blade.php` | Nonce on all inline scripts/styles |
| `resources/views/auth/login.blade.php` | Nonce on auth page scripts |
| `resources/views/auth/register.blade.php` | Nonce on auth page scripts |
| `resources/views/landing.blade.php` | Nonce on landing page scripts |
| `routes/web.php` | Fixed open redirect in `/lang/{locale}` |
| `vercel.json` | Upgraded to vercel-php@0.9.0, edge headers |

---

## Next Steps

1. **Deploy to Vercel** → Push changes to GitHub → Auto-deploys
2. **Configure Cloudflare** → Follow this guide
3. **Test thoroughly** → Use checklist above
4. **Monitor** → Check Cloudflare Analytics daily for first week
5. **Tune WAF** → Adjust rules based on false positives/negatives