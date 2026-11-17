<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/app.php';

$csrfToken = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Student Hub — the final project of Mark Louis Pamisa, integrating a personal profile, TaskFlow, an XML directory, and PHP API labs in one site.">
  <meta name="theme-color" content="#163b32">
  <meta name="csrf-token" content="<?= e($csrfToken) ?>">
  <title>Student Hub · Mark Louis Pamisa</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="assets/styles.css">
  <script src="assets/app.js" defer></script>
  <script src="assets/tasks.js" defer></script>
  <script src="assets/directory.js" defer></script>
  <script src="assets/labs.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header" data-site-header>
    <div class="nav-shell">
      <a class="brand" href="#home" aria-label="Student Hub home">
        <span class="brand-mark" aria-hidden="true">ML<span>.</span></span>
        <span class="brand-copy">
          <strong>Student Hub</strong>
          <span>Mark Louis Pamisa</span>
        </span>
      </a>

      <button class="nav-toggle" id="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        <span></span>
        <span></span>
        <span class="sr-only">Toggle navigation</span>
      </button>

      <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a class="nav-link is-active" href="#home" data-nav="home" aria-current="page">Home</a>
        <a class="nav-link" href="#tasks" data-nav="tasks">Tasks</a>
        <a class="nav-link" href="#directory" data-nav="directory">Directory</a>
        <a class="nav-link" href="#lab" data-nav="lab">Labs</a>
        <a class="nav-link nav-link-cta" href="#contact" data-nav="contact">Say hello <span aria-hidden="true">↗</span></a>
      </nav>
    </div>
  </header>

  <main id="main">
    <section class="hero" id="home" aria-labelledby="hero-title">
      <div class="container hero-grid">
        <div class="hero-copy">
          <p class="eyebrow"><span class="eyebrow-dot"></span> Final project · One site, every lesson</p>
          <h1 id="hero-title">Build, learn,<br><em>keep moving.</em></h1>
          <p class="hero-role">Mark Louis Pamisa <span>BS Information Technology</span></p>
          <p class="hero-lede">I’m a BSIT student at Pamantasan ng Lungsod ng Muntinlupa. This Student Hub brings my profile, task board, XML directory, and API exercises together in one working site.</p>

          <div class="hero-actions">
            <a class="button button-dark" href="#tasks">Open the workspace <span aria-hidden="true">↘</span></a>
            <a class="button button-quiet" href="#lab">Open lab playground</a>
          </div>

          <div class="hero-socials" aria-label="Contact links">
            <a class="social-link" href="https://github.com/Bulbasur123" target="_blank" rel="noopener noreferrer" aria-label="GitHub profile">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 .7a11.5 11.5 0 0 0-3.64 22.4c.58.1.79-.25.79-.56v-2.2c-3.22.7-3.9-1.37-3.9-1.37-.53-1.34-1.29-1.7-1.29-1.7-1.05-.72.08-.71.08-.71 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.71 1.26 3.37.96.1-.75.4-1.26.73-1.55-2.57-.29-5.27-1.28-5.27-5.7 0-1.26.45-2.29 1.19-3.1-.12-.29-.52-1.47.11-3.06 0 0 .97-.31 3.17 1.18a11 11 0 0 1 5.77 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.77.11 3.06.74.81 1.19 1.84 1.19 3.1 0 4.43-2.7 5.4-5.28 5.69.41.36.78 1.08.78 2.18v3.23c0 .31.21.67.8.56A11.5 11.5 0 0 0 12 .7Z" />
              </svg>
            </a>
            <a class="social-link" href="mailto:pamisamarklouis_bsit@plmun.edu.ph" aria-label="Send an email">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="m3 7 9 6 9-6" />
              </svg>
            </a>
            <span class="social-note">Available for small collaborations and feedback.</span>
          </div>
        </div>

        <div class="hero-visual">
          <div class="portrait-card">
            <div class="portrait-top">
              <span class="portrait-label">Student profile</span>
              <span class="portrait-status"><span></span> 2026</span>
            </div>
            <div class="portrait-frame">
              <img src="assets/profile.jpg" alt="Portrait of Mark Louis Pamisa" width="768" height="768" fetchpriority="high">
            </div>
            <div class="portrait-foot">
              <span>Mark Louis Pamisa</span>
              <span>PLMun · BSIT</span>
            </div>
          </div>

          <div class="floating-card floating-card-a">
            <span class="floating-dot"></span>
            <span><strong>Student Hub</strong><small>Final project · live</small></span>
          </div>

          <div class="floating-card floating-card-b">
            <span class="floating-number">05</span>
            <span><strong>Lab exercises</strong><small>integrated</small></span>
          </div>
        </div>
      </div>

      <div class="container">
        <ul class="quick-facts" aria-label="Quick details">
          <li><span>Course</span><strong>BS Information Technology</strong></li>
          <li><span>School</span><strong>Pamantasan ng Lungsod ng Muntinlupa</strong></li>
          <li><span>Location</span><strong>Muntinlupa City</strong></li>
          <li><span>Focus</span><strong>Web interfaces &amp; practical systems</strong></li>
        </ul>
      </div>
    </section>

    <section class="section" id="tasks" aria-labelledby="tasks-section-title">
      <div class="container">
        <div class="section-heading">
          <div>
            <p class="eyebrow"><span class="eyebrow-dot"></span> 01 · TaskFlow</p>
            <h2 id="tasks-section-title">Make room for<br><em>what matters.</em></h2>
          </div>
          <p class="section-note">A PHP-backed task board from Exercise #2. Add, finish, search, and filter tasks without leaving the page; every change is saved to a JSON file on the server.</p>
        </div>

        <div class="task-dashboard">
          <aside class="task-panel task-summary" aria-label="Task summary and new task form">
            <div class="summary-top">
              <p class="panel-kicker">Your progress</p>
              <span class="status-pill"><span class="status-dot"></span> Auto-saved</span>
            </div>

            <div class="progress-wrap">
              <div class="progress-ring" id="progress-ring" style="--progress: 0%;" role="img" aria-label="Loading tasks">
                <div class="progress-inner">
                  <strong id="progress-value">0%</strong>
                  <span>complete</span>
                </div>
              </div>
              <div class="progress-copy">
                <p><strong id="stat-total">0</strong> tasks on the board</p>
                <div class="mini-stats">
                  <span><strong id="stat-active">0</strong> to do</span>
                  <span><strong id="stat-done">0</strong> done</span>
                </div>
              </div>
            </div>

            <div class="summary-divider"></div>

            <form id="task-form" class="task-form" method="post" action="api/tasks.php">
              <div class="form-heading">
                <h3>Add a task</h3>
                <span>Small steps count.</span>
              </div>

              <label class="field" for="task-title">
                <span>What needs doing?</span>
                <input id="task-title" name="title" type="text" maxlength="120" placeholder="e.g. Test the Fetch demo" autocomplete="off" required>
              </label>

              <div class="form-row">
                <label class="field" for="task-priority">
                  <span>Priority</span>
                  <select id="task-priority" name="priority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                  </select>
                </label>

                <label class="field" for="task-due">
                  <span>Due date <small>optional</small></span>
                  <input id="task-due" name="due" type="date">
                </label>
              </div>

              <input type="hidden" name="action" value="add">
              <button class="add-button" id="add-button" type="submit" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 5v14M5 12h14" />
                </svg>
                <span id="add-button-label">Add task</span>
              </button>
            </form>
          </aside>

          <section class="task-panel task-board" aria-labelledby="task-board-title">
            <div class="tasks-head">
              <div>
                <p class="panel-kicker">The list</p>
                <h3 id="task-board-title">Today’s focus</h3>
              </div>
              <button class="clear-button" id="clear-completed" type="button" disabled>Clear done</button>
            </div>

            <div class="task-toolbar">
              <div class="task-filters" role="group" aria-label="Filter tasks">
                <button class="task-filter is-active" type="button" aria-pressed="true" data-task-filter="all">All <span data-task-count="all">0</span></button>
                <button class="task-filter" type="button" aria-pressed="false" data-task-filter="active">To do <span data-task-count="active">0</span></button>
                <button class="task-filter" type="button" aria-pressed="false" data-task-filter="done">Done <span data-task-count="done">0</span></button>
              </div>

              <label class="search-field" for="search-tasks">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="11" cy="11" r="6.5" />
                  <path d="m16 16 4.5 4.5" />
                </svg>
                <input id="search-tasks" type="search" placeholder="Search tasks" autocomplete="off">
              </label>
            </div>

            <noscript>
              <p class="inline-note">JavaScript is required to load and update the task board.</p>
            </noscript>

            <ul class="task-list" id="task-list" aria-live="polite" aria-busy="true"></ul>

            <div class="empty-state" id="empty-state" hidden>
              <div class="empty-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12.5 9 16l10-10" />
                </svg>
              </div>
              <h4 id="empty-title">A clear board.</h4>
              <p id="empty-text">Add your first task on the left and it will show up here.</p>
            </div>

            <p class="list-footnote" id="list-footnote">Loading tasks…</p>
          </section>
        </div>
      </div>
    </section>

    <section class="section directory-section" id="directory" aria-labelledby="directory-section-title">
      <div class="container">
        <div class="section-heading">
          <div>
            <p class="eyebrow"><span class="eyebrow-dot"></span> 02 · XML directory</p>
            <h2 id="directory-section-title">A small<br><em>student archive.</em></h2>
          </div>
          <p class="section-note">The XML activity, brought into the site. These records are read from <code>data/student_data.xml</code> with XMLHttpRequest, then rendered as a searchable directory.</p>
        </div>

        <div class="directory-panel">
          <div class="directory-toolbar">
            <div class="source-label">
              <span class="source-icon" aria-hidden="true">XML</span>
              <span><strong>student_data.xml</strong><small>Loaded via XMLHttpRequest</small></span>
            </div>

            <label class="search-field directory-search" for="directory-search">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" />
                <path d="m16 16 4.5 4.5" />
              </svg>
              <input id="directory-search" type="search" placeholder="Search name or course" autocomplete="off">
            </label>
          </div>

          <div class="directory-meta">
            <span class="directory-status" id="directory-status" role="status" aria-live="polite"><span class="status-dot"></span> Reading XML…</span>
            <span id="directory-count">3 records</span>
          </div>

          <div class="directory-grid" id="directory-list" aria-live="polite" aria-busy="true"></div>

          <noscript>
            <p class="inline-note">JavaScript is required to load the XML directory.</p>
          </noscript>
        </div>
      </div>
    </section>

    <section class="section section-dark" id="lab" aria-labelledby="lab-section-title">
      <div class="container">
        <div class="section-heading section-heading-dark">
          <div>
            <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> 03 · Lab playground</p>
            <h2 id="lab-section-title">Five labs.<br><em>One playground.</em></h2>
          </div>
          <p class="section-note section-note-light">The JSON and PHP exercises become live endpoints here. Open a tab to test encoding, decoding, raw JSON, AJAX, XMLHttpRequest, and Fetch.</p>
        </div>

        <div class="lab-shell">
          <div class="lab-tabs" role="tablist" aria-label="Choose a lab group">
            <button class="lab-tab is-active" id="tab-json-button" type="button" role="tab" aria-selected="true" aria-controls="tab-json" data-lab-tab="json">
              JSON + PHP <span>Labs 1–5</span>
            </button>
            <button class="lab-tab" id="tab-xhr-button" type="button" role="tab" aria-selected="false" aria-controls="tab-xhr" data-lab-tab="xhr">
              XMLHttpRequest <span>Lab 2</span>
            </button>
            <button class="lab-tab" id="tab-fetch-button" type="button" role="tab" aria-selected="false" aria-controls="tab-fetch" data-lab-tab="fetch">
              Fetch API <span>Lab 3</span>
            </button>
          </div>

          <div class="lab-panels">
            <section class="lab-panel" id="tab-json" role="tabpanel" aria-labelledby="tab-json-button">
              <div class="lab-grid">
                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 1</span>
                    <span class="lab-tag">Encode</span>
                  </div>
                  <h3>Encode a student</h3>
                  <p>Turn an associative array into a compact JSON string.</p>
                  <button class="lab-action" id="encode-button" type="button">Run encode <span aria-hidden="true">↗</span></button>
                  <pre class="lab-output" id="encode-output" aria-live="polite">Response will appear here.</pre>
                </article>

                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 2</span>
                    <span class="lab-tag">Decode</span>
                  </div>
                  <h3>Decode both ways</h3>
                  <p>Read one JSON string as an object and as an associative array.</p>
                  <button class="lab-action" id="decode-button" type="button">Decode sample <span aria-hidden="true">↗</span></button>
                  <pre class="lab-output" id="decode-output" aria-live="polite">Object: —<br>Array: —</pre>
                </article>

                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 3</span>
                    <span class="lab-tag">API</span>
                  </div>
                  <h3>Read a profile</h3>
                  <p>Call a PHP endpoint that returns a JSON user profile.</p>
                  <button class="lab-action" id="profile-button" type="button">Call API <span aria-hidden="true">↗</span></button>
                  <pre class="lab-output" id="profile-output" aria-live="polite">Response will appear here.</pre>
                </article>

                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 4</span>
                    <span class="lab-tag">Raw input</span>
                  </div>
                  <h3>Send raw JSON</h3>
                  <p>Edit the request body and send it to PHP without a form.</p>
                  <textarea class="lab-textarea" id="raw-json-input" rows="5" spellcheck="false" aria-label="Raw JSON request body">{"username":"admin","password":"1234"}</textarea>
                  <button class="lab-action" id="raw-json-button" type="button">Send JSON <span aria-hidden="true">↗</span></button>
                  <pre class="lab-output" id="raw-json-output" aria-live="polite">Response will appear here.</pre>
                </article>

                <article class="lab-card lab-card-wide">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 5</span>
                    <span class="lab-tag">AJAX</span>
                  </div>
                  <div class="lab-card-split">
                    <div>
                      <h3>Send a name with AJAX</h3>
                      <p>Submit a small form with Fetch and display the JSON response without reloading.</p>
                      <div class="inline-form">
                        <label class="field field-light" for="welcome-name">
                          <span>Name</span>
                          <input id="welcome-name" type="text" placeholder="Maria" maxlength="80" autocomplete="given-name">
                        </label>
                        <button class="lab-action" id="welcome-button" type="button">Send request <span aria-hidden="true">↗</span></button>
                      </div>
                    </div>
                    <pre class="lab-output lab-output-tall" id="welcome-output" aria-live="polite">Response will appear here.</pre>
                  </div>
                </article>
              </div>
            </section>

            <section class="lab-panel" id="tab-xhr" role="tabpanel" aria-labelledby="tab-xhr-button" hidden>
              <div class="lab-grid lab-grid-two">
                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 2</span>
                    <span class="lab-tag">XHR</span>
                  </div>
                  <h3>Load a post with XMLHttpRequest</h3>
                  <p>An asynchronous GET request reads one post from the playground API and displays its title and body.</p>
                  <button class="lab-action" id="xhr-button" type="button">Load post via XHR <span aria-hidden="true">↗</span></button>
                  <div class="request-status" id="xhr-status"><span class="status-dot"></span> Idle</div>
                  <div class="external-output" id="xhr-output" aria-live="polite">
                    <p class="placeholder-copy">Click the button to inspect a post.</p>
                  </div>
                </article>

                <aside class="lab-note-card">
                  <p class="panel-kicker">The lifecycle</p>
                  <h3>Watch the states</h3>
                  <p>XMLHttpRequest moves through <code>readyState</code> values while the request is running. A complete response at state 4 still needs a status check. This demo reads from the same-origin PHP playground, so no third-party service is involved.</p>
                  <ul class="state-list">
                    <li><span>1</span> Opened</li>
                    <li><span>2</span> Headers received</li>
                    <li><span>3</span> Loading</li>
                    <li><span>4</span> Done &amp; ready to render</li>
                  </ul>
                  <pre class="code-note">xhr.open("GET", url, true);
xhr.onreadystatechange = () =&gt; {
  if (xhr.readyState === 4) {
    if (xhr.status === 200) render(xhr.responseText);
  }
};</pre>
                </aside>
              </div>
            </section>

            <section class="lab-panel" id="tab-fetch" role="tabpanel" aria-labelledby="tab-fetch-button" hidden>
              <div class="lab-grid lab-grid-two">
                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 3</span>
                    <span class="lab-tag">GET</span>
                  </div>
                  <h3>Fetch a post</h3>
                  <p>Read post #1 with a promise-based Fetch request from the playground API and render the title and body.</p>
                  <button class="lab-action" id="fetch-get-button" type="button">Fetch GET <span aria-hidden="true">↗</span></button>
                  <div class="request-status" id="fetch-get-status"><span class="status-dot"></span> Idle</div>
                  <div class="external-output" id="fetch-get-output" aria-live="polite">
                    <p class="placeholder-copy">Response will appear here.</p>
                  </div>
                </article>

                <article class="lab-card">
                  <div class="lab-card-top">
                    <span class="lab-index">Lab 3</span>
                    <span class="lab-tag">POST</span>
                  </div>
                  <h3>Create a post</h3>
                  <p>Send the sample payload to the same PHP endpoint with <code>method: "POST"</code> and display the returned object.</p>
                  <button class="lab-action" id="fetch-post-button" type="button">Fetch POST <span aria-hidden="true">↗</span></button>
                  <div class="request-status" id="fetch-post-status"><span class="status-dot"></span> Idle</div>
                  <pre class="lab-output" id="fetch-post-output" aria-live="polite">Response will appear here.</pre>
                </article>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>

    <section class="section contact-section" id="contact" aria-labelledby="contact-section-title">
      <div class="container">
        <div class="contact-card">
          <div class="contact-copy">
            <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> 04 · Contact</p>
            <h2 id="contact-section-title">Say<br><em>hello.</em></h2>
            <p>Have feedback on the site, a question about one of the labs, or a small project in mind? Send a note through the form.</p>

            <ul class="contact-list">
              <li>
                <span>Email</span>
                <a href="mailto:pamisamarklouis_bsit@plmun.edu.ph">pamisamarklouis_bsit@plmun.edu.ph</a>
              </li>
              <li>
                <span>GitHub</span>
                <a href="https://github.com/Bulbasur123" target="_blank" rel="noopener noreferrer">github.com/Bulbasur123</a>
              </li>
              <li>
                <span>Location</span>
                <span>Muntinlupa City, Philippines</span>
              </li>
            </ul>

            <p class="contact-note"><span class="status-dot"></span> Native HTML form · PHP POST · no data stored</p>
          </div>

          <form class="contact-form" action="contact.php" method="post">
            <input type="hidden" name="csrf" value="<?= e($csrfToken) ?>">

            <div class="contact-form-head">
              <p class="panel-kicker">Send a message</p>
              <span>Usually a short reply.</span>
            </div>

            <div class="form-grid">
              <label class="field" for="contact-name">
                <span>Name</span>
                <input id="contact-name" name="name" type="text" maxlength="80" placeholder="Your name" autocomplete="name" required>
              </label>

              <label class="field" for="contact-email">
                <span>Email</span>
                <input id="contact-email" name="email" type="email" maxlength="120" placeholder="you@example.com" autocomplete="email" required>
              </label>
            </div>

            <label class="field" for="contact-role">
              <span>I am a</span>
              <select id="contact-role" name="role" required>
                <option value="">Choose one</option>
                <option value="Classmate">Classmate</option>
                <option value="Instructor">Instructor</option>
                <option value="Collaborator">Collaborator</option>
                <option value="Other">Other</option>
              </select>
            </label>

            <label class="field" for="contact-message">
              <span>Message</span>
              <textarea id="contact-message" name="message" rows="5" maxlength="500" placeholder="Write a short note…" required></textarea>
            </label>

            <button class="contact-submit" type="submit">Send message <span aria-hidden="true">↗</span></button>
            <p class="form-note">The form is handled by <code>contact.php</code> on the server. No message is saved.</p>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <a class="brand brand-footer" href="#home">
          <span class="brand-mark" aria-hidden="true">ML<span>.</span></span>
          <span class="brand-copy"><strong>Student Hub</strong><span>Final project · 2026</span></span>
        </a>
        <p>A single place for a profile, a task board, an XML archive, and the labs behind it.</p>
      </div>

      <nav class="footer-links" aria-label="Footer navigation">
        <div>
          <span>Explore</span>
          <a href="#home">Home</a>
          <a href="#tasks">Tasks</a>
          <a href="#directory">Directory</a>
          <a href="#lab">Labs</a>
        </div>
        <div>
          <span>Connect</span>
          <a href="#contact">Contact</a>
          <a href="https://github.com/Bulbasur123" target="_blank" rel="noopener noreferrer">GitHub</a>
          <a href="mailto:pamisamarklouis_bsit@plmun.edu.ph">Email</a>
        </div>
      </nav>

      <div class="footer-bottom">
        <span>© <span id="current-year">2026</span> Mark Louis Pamisa</span>
        <span>PHP · JSON · XML · Fetch · XHR</span>
        <a href="#home">Back to top ↑</a>
      </div>
    </div>
  </footer>

  <div class="toast" id="toast" role="status" aria-live="polite" aria-atomic="true">
    <span class="toast-dot" aria-hidden="true"></span>
    <span id="toast-message"></span>
  </div>
</body>
</html>
