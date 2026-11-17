/* ==========================================================================
   Student Hub — lab playground
   JSON + PHP endpoints, XMLHttpRequest, and the Fetch API.
   ========================================================================== */

(() => {
  "use strict";

  const API = "api/lab.php";

  const encodeButton = document.getElementById("encode-button");
  const encodeOutput = document.getElementById("encode-output");
  const decodeButton = document.getElementById("decode-button");
  const decodeOutput = document.getElementById("decode-output");
  const profileButton = document.getElementById("profile-button");
  const profileOutput = document.getElementById("profile-output");
  const rawInput = document.getElementById("raw-json-input");
  const rawButton = document.getElementById("raw-json-button");
  const rawOutput = document.getElementById("raw-json-output");
  const welcomeName = document.getElementById("welcome-name");
  const welcomeButton = document.getElementById("welcome-button");
  const welcomeOutput = document.getElementById("welcome-output");
  const xhrButton = document.getElementById("xhr-button");
  const xhrStatus = document.getElementById("xhr-status");
  const xhrOutput = document.getElementById("xhr-output");
  const fetchGetButton = document.getElementById("fetch-get-button");
  const fetchGetStatus = document.getElementById("fetch-get-status");
  const fetchGetOutput = document.getElementById("fetch-get-output");
  const fetchPostButton = document.getElementById("fetch-post-button");
  const fetchPostOutput = document.getElementById("fetch-post-output");

  const pretty = (value) => JSON.stringify(value, null, 2);

  const setOutput = (element, text, isError = false) => {
    if (!element) {
      return;
    }

    element.textContent = text;
    element.classList.toggle("is-error", isError);
    element.classList.toggle("is-success", !isError);
  };

  const setStatus = (element, text, state = "") => {
    if (!element) {
      return;
    }

    const dot = document.createElement("span");
    dot.className = "status-dot";

    element.replaceChildren(dot, document.createTextNode(` ${text}`));
    element.classList.remove("is-loading", "is-success", "is-error");

    if (state) {
      element.classList.add(`is-${state}`);
    }
  };

  const setButtonBusy = (button, busy, label = "Working…") => {
    if (!button) {
      return;
    }

    if (busy) {
      button.dataset.idleHtml = button.innerHTML;
      button.disabled = true;
      button.textContent = label;
      return;
    }

    button.disabled = false;

    if (button.dataset.idleHtml) {
      button.innerHTML = button.dataset.idleHtml;
      delete button.dataset.idleHtml;
    }
  };

  const fetchJson = async (url, options = {}) => {
    const response = await fetch(url, {
      credentials: "same-origin",
      ...options,
      headers: {
        Accept: "application/json",
        ...(options.headers || {}),
      },
    });

    const text = await response.text();
    let data = null;

    if (text) {
      try {
        data = JSON.parse(text);
      } catch {
        throw new Error("The endpoint returned a response that was not valid JSON.");
      }
    }

    if (!response.ok) {
      throw new Error(
        data && typeof data.message === "string"
          ? data.message
          : `The endpoint returned HTTP ${response.status}.`
      );
    }

    return data;
  };

  const errorMessage = (error) =>
    error instanceof Error ? error.message : "Something went wrong. Please try again.";

  const renderPost = (element, post, extraMeta = "") => {
    if (!element) {
      return;
    }

    element.replaceChildren();

    const meta = document.createElement("div");
    meta.className = "post-meta";
    meta.textContent = `Post #${post.id} · User ${post.userId}${extraMeta ? ` · ${extraMeta}` : ""}`;

    const title = document.createElement("h4");
    title.className = "post-title";
    title.textContent = post.title;

    const body = document.createElement("p");
    body.className = "post-body";
    body.textContent = post.body;

    const raw = document.createElement("pre");
    raw.className = "post-json";
    raw.textContent = pretty(post);

    element.append(meta, title, body, raw);
  };

  /* JSON + PHP cards ------------------------------------------------------ */

  encodeButton?.addEventListener("click", async () => {
    setButtonBusy(encodeButton, true, "Encoding…");
    setOutput(encodeOutput, "Calling the PHP endpoint…");

    try {
      const data = await fetchJson(`${API}?action=encode`);
      setOutput(encodeOutput, pretty(data));
    } catch (error) {
      setOutput(encodeOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(encodeButton, false);
    }
  });

  decodeButton?.addEventListener("click", async () => {
    setButtonBusy(decodeButton, true, "Decoding…");
    setOutput(decodeOutput, "Decoding the sample JSON…");

    try {
      const data = await fetchJson(`${API}?action=decode`);
      setOutput(decodeOutput, `Object: ${data.object}\nArray: ${data.array}`);
    } catch (error) {
      setOutput(decodeOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(decodeButton, false);
    }
  });

  profileButton?.addEventListener("click", async () => {
    setButtonBusy(profileButton, true, "Calling API…");
    setOutput(profileOutput, "Calling the profile endpoint…");

    try {
      const data = await fetchJson(`${API}?action=profile`);
      setOutput(profileOutput, pretty(data));
    } catch (error) {
      setOutput(profileOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(profileButton, false);
    }
  });

  rawButton?.addEventListener("click", async () => {
    const raw = rawInput?.value.trim() || "";

    if (!raw) {
      setOutput(rawOutput, "Paste a JSON request body first.", true);
      rawInput?.focus();
      return;
    }

    setButtonBusy(rawButton, true, "Sending JSON…");
    setOutput(rawOutput, "Sending the raw request body…");

    try {
      const data = await fetchJson(`${API}?action=input`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: raw,
      });

      const lines = [];

      if (typeof data.output === "string") {
        lines.push(data.output, "");
      }

      lines.push("Response JSON:", pretty(data));
      setOutput(rawOutput, lines.join("\n"));
    } catch (error) {
      setOutput(rawOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(rawButton, false);
    }
  });

  welcomeButton?.addEventListener("click", async () => {
    const name = welcomeName?.value.trim() || "";

    if (!name) {
      setOutput(welcomeOutput, "Enter a name before sending the request.", true);
      welcomeName?.focus();
      return;
    }

    setButtonBusy(welcomeButton, true, "Sending…");
    setOutput(welcomeOutput, "Sending the name to the PHP endpoint…");

    try {
      const data = await fetchJson(`${API}?action=welcome`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ name }),
      });

      setOutput(welcomeOutput, pretty(data));
    } catch (error) {
      setOutput(welcomeOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(welcomeButton, false);
    }
  });

  /* XMLHttpRequest -------------------------------------------------------- */

  xhrButton?.addEventListener("click", () => {
    setButtonBusy(xhrButton, true, "Loading via XHR…");
    setStatus(xhrStatus, "Loading…", "loading");

    if (xhrOutput) {
      xhrOutput.classList.remove("is-error");
      xhrOutput.textContent = "Waiting for XMLHttpRequest…";
    }

    const xhr = new XMLHttpRequest();
    let finished = false;

    const finishWithError = (message) => {
      if (finished) {
        return;
      }

      finished = true;
      setButtonBusy(xhrButton, false);
      setStatus(xhrStatus, "Request failed", "error");

      if (xhrOutput) {
        xhrOutput.classList.add("is-error");
        xhrOutput.textContent = message;
      }
    };

    xhr.open("GET", `${API}?action=post`, true);
    xhr.timeout = 10000;
    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    xhr.onreadystatechange = () => {
      if (xhr.readyState !== 4 || finished) {
        return;
      }

      if (xhr.status < 200 || xhr.status >= 300) {
        finishWithError(`The request returned HTTP ${xhr.status}.`);
        return;
      }

      try {
        const data = JSON.parse(xhr.responseText);
        finished = true;
        setButtonBusy(xhrButton, false);
        setStatus(xhrStatus, `HTTP ${xhr.status} · readyState 4`, "success");
        renderPost(xhrOutput, data);
      } catch {
        finishWithError("The response was not valid JSON.");
      }
    };

    xhr.onerror = () => finishWithError("The XMLHttpRequest could not reach the endpoint.");
    xhr.ontimeout = () => finishWithError("The XMLHttpRequest timed out.");
    xhr.send();
  });

  /* Fetch API ------------------------------------------------------------- */

  fetchGetButton?.addEventListener("click", async () => {
    setButtonBusy(fetchGetButton, true, "Fetching…");
    setStatus(fetchGetStatus, "Loading…", "loading");

    if (fetchGetOutput) {
      fetchGetOutput.classList.remove("is-error");
      fetchGetOutput.textContent = "Waiting for the Fetch API…";
    }

    try {
      const data = await fetchJson(`${API}?action=post`);
      setStatus(fetchGetStatus, "HTTP 200 · promise resolved", "success");
      renderPost(fetchGetOutput, data);
    } catch (error) {
      setStatus(fetchGetStatus, "Request failed", "error");

      if (fetchGetOutput) {
        fetchGetOutput.classList.add("is-error");
        fetchGetOutput.textContent = errorMessage(error);
      }
    } finally {
      setButtonBusy(fetchGetButton, false);
    }
  });

  fetchPostButton?.addEventListener("click", async () => {
    setButtonBusy(fetchPostButton, true, "Sending POST…");
    setOutput(fetchPostOutput, "Sending the sample payload…");

    try {
      const data = await fetchJson(`${API}?action=post`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          title: "A note from Student Hub",
          body: "Created with the Fetch API inside the final project.",
          userId: 1,
        }),
      });

      setOutput(fetchPostOutput, pretty(data));
    } catch (error) {
      setOutput(fetchPostOutput, errorMessage(error), true);
    } finally {
      setButtonBusy(fetchPostButton, false);
    }
  });
})();
