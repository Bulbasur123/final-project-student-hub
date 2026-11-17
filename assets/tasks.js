/* ==========================================================================
   Student Hub — TaskFlow
   The Exercise #2 task manager, adapted for the integrated site.
   ========================================================================== */

(() => {
  "use strict";

  const API_URL = "api/tasks.php";

  const CHECK_ICON = `
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="m5 12 4 4L19 6" />
    </svg>`;

  const TRASH_ICON = `
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M4 7h16M9 7V4.8c0-.5.4-.8.8-.8h4.4c.4 0 .8.3.8.8V7M6.5 7l.8 12.1c0 .5.4.9.9.9h7.6c.5 0 .9-.4.9-.9L17.5 7M10 11v5.5M14 11v5.5" />
    </svg>`;

  const CALENDAR_ICON = `
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="4" y="5.5" width="16" height="15" rx="3" />
      <path d="M8 3.5v4M16 3.5v4M4 10h16" />
    </svg>`;

  const form = document.getElementById("task-form");
  const titleInput = document.getElementById("task-title");
  const priorityInput = document.getElementById("task-priority");
  const dueInput = document.getElementById("task-due");
  const addButton = document.getElementById("add-button");
  const addButtonLabel = document.getElementById("add-button-label");
  const list = document.getElementById("task-list");
  const emptyState = document.getElementById("empty-state");
  const emptyTitle = document.getElementById("empty-title");
  const emptyText = document.getElementById("empty-text");
  const searchInput = document.getElementById("search-tasks");
  const clearButton = document.getElementById("clear-completed");
  const progressRing = document.getElementById("progress-ring");
  const progressValue = document.getElementById("progress-value");
  const statTotal = document.getElementById("stat-total");
  const statActive = document.getElementById("stat-active");
  const statDone = document.getElementById("stat-done");
  const listFootnote = document.getElementById("list-footnote");
  const filterButtons = [...document.querySelectorAll("[data-task-filter]")];
  const filterCounts = [...document.querySelectorAll("[data-task-count]")];
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toast-message");

  if (!form || !list || !addButton) {
    return;
  }

  const state = {
    tasks: [],
    stats: { total: 0, done: 0, active: 0, percent: 0 },
    csrfToken: "",
    filter: "all",
    query: "",
    loading: true,
    busy: false,
    error: "",
  };

  let toastTimer = 0;

  const escapeText = (value) => String(value ?? "");

  const computeStats = (tasks) => {
    const total = tasks.length;
    const done = tasks.filter((task) => task.done).length;
    const active = total - done;

    return {
      total,
      done,
      active,
      percent: total > 0 ? Math.round((done / total) * 100) : 0,
    };
  };

  const showToast = (message, type = "success") => {
    if (!toast || !toastMessage) {
      return;
    }

    window.clearTimeout(toastTimer);
    toastMessage.textContent = message;
    toast.classList.toggle("is-error", type === "error");
    toast.classList.add("is-visible");

    toastTimer = window.setTimeout(() => {
      toast.classList.remove("is-visible");
    }, 3400);
  };

  const syncAddButton = () => {
    const hasTitle = Boolean(titleInput?.value.trim());
    addButton.disabled = !hasTitle || state.loading || state.busy;
  };

  const setAddBusy = (busy) => {
    state.busy = busy;
    addButtonLabel.textContent = busy ? "Adding…" : "Add task";
    syncAddButton();
  };

  const request = async (payload) => {
    const response = await fetch(API_URL, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-CSRF-Token": state.csrfToken,
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify(payload),
    });

    let data;

    try {
      data = await response.json();
    } catch {
      throw new Error("The server returned an unreadable response.");
    }

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "The task could not be updated.");
    }

    return data;
  };

  const applyBoard = (data) => {
    state.tasks = Array.isArray(data.tasks) ? data.tasks : [];
    state.stats = data.stats && typeof data.stats === "object"
      ? data.stats
      : computeStats(state.tasks);
    state.error = "";
  };

  const formatDate = (value) => {
    if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
      return null;
    }

    const [year, month, day] = value.split("-").map(Number);
    const date = new Date(year, month - 1, day);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (Number.isNaN(date.getTime())) {
      return null;
    }

    const monthDay = new Intl.DateTimeFormat("en", {
      month: "short",
      day: "numeric",
    }).format(date);

    const diff = Math.round((date - today) / 86400000);

    if (diff < 0) {
      return { label: `Overdue · ${monthDay}`, overdue: true };
    }

    if (diff === 0) {
      return { label: "Due today", overdue: false };
    }

    if (diff === 1) {
      return { label: "Due tomorrow", overdue: false };
    }

    return { label: `Due ${monthDay}`, overdue: false };
  };

  const makeTaskElement = (task) => {
    const item = document.createElement("li");
    item.className = "task";
    item.dataset.taskId = task.id;

    if (task.done) {
      item.classList.add("is-done");
    }

    const check = document.createElement("button");
    check.type = "button";
    check.className = "task-check";
    check.dataset.action = "toggle";
    check.setAttribute("aria-pressed", String(Boolean(task.done)));
    check.setAttribute("aria-label", task.done ? `Mark “${task.title}” as not done` : `Complete “${task.title}”`);
    check.innerHTML = CHECK_ICON;

    const copy = document.createElement("div");
    copy.className = "task-copy";

    const title = document.createElement("p");
    title.className = "task-title";
    title.textContent = escapeText(task.title);
    copy.append(title);

    const meta = document.createElement("div");
    meta.className = "task-meta";

    const priority = document.createElement("span");
    priority.className = `priority priority-${task.priority}`;
    priority.textContent = `${task.priority} priority`;
    meta.append(priority);

    const due = formatDate(task.due);

    if (due) {
      const dueNode = document.createElement("span");
      dueNode.className = "due";
      dueNode.innerHTML = CALENDAR_ICON;

      if (due.overdue && !task.done) {
        dueNode.classList.add("is-overdue");
      }

      dueNode.append(document.createTextNode(due.label));
      meta.append(dueNode);
    }

    copy.append(meta);

    const remove = document.createElement("button");
    remove.type = "button";
    remove.className = "task-delete";
    remove.dataset.action = "delete";
    remove.setAttribute("aria-label", `Delete “${task.title}”`);
    remove.innerHTML = TRASH_ICON;

    item.append(check, copy, remove);

    return item;
  };

  const getVisibleTasks = () => {
    const query = state.query.trim().toLowerCase();

    return state.tasks.filter((task) => {
      const matchesFilter =
        state.filter === "all" ||
        (state.filter === "done" && task.done) ||
        (state.filter === "active" && !task.done);

      if (!matchesFilter) {
        return false;
      }

      if (!query) {
        return true;
      }

      return `${task.title} ${task.priority}`.toLowerCase().includes(query);
    });
  };

  const updateSummary = () => {
    const stats = state.stats || computeStats(state.tasks);
    const percent = Math.max(0, Math.min(100, Number(stats.percent) || 0));

    progressRing?.style.setProperty("--progress", `${percent}%`);

    if (progressRing) {
      progressRing.setAttribute("aria-label", `${percent}% of tasks complete`);
    }

    if (progressValue) {
      progressValue.textContent = `${percent}%`;
    }

    if (statTotal) {
      statTotal.textContent = String(stats.total ?? state.tasks.length);
    }

    if (statActive) {
      statActive.textContent = String(stats.active ?? 0);
    }

    if (statDone) {
      statDone.textContent = String(stats.done ?? 0);
    }

    if (clearButton) {
      clearButton.disabled = (stats.done ?? 0) === 0 || state.busy;
    }

    filterCounts.forEach((node) => {
      const key = node.dataset.taskCount;
      node.textContent = String(
        key === "active"
          ? stats.active ?? 0
          : key === "done"
            ? stats.done ?? 0
            : stats.total ?? 0
      );
    });
  };

  const render = () => {
    const visible = getVisibleTasks();

    list.replaceChildren();

    if (state.loading) {
      listFootnote.textContent = "Loading tasks…";
      list.setAttribute("aria-busy", "true");
      emptyState.hidden = true;
      updateSummary();
      return;
    }

    list.setAttribute("aria-busy", "false");

    if (state.error) {
      const errorItem = document.createElement("li");
      errorItem.className = "directory-empty";
      const errorTitle = document.createElement("strong");
      errorTitle.textContent = "Tasks could not be loaded.";
      const errorCopy = document.createElement("span");
      errorCopy.textContent = state.error;
      errorItem.append(errorTitle, errorCopy);
      list.append(errorItem);
      emptyState.hidden = true;
      listFootnote.textContent = "Storage unavailable · try refreshing the page.";
      updateSummary();
      return;
    }

    if (visible.length === 0) {
      emptyState.hidden = false;
      listFootnote.textContent = "No tasks match this view.";
      updateSummary();

      if (state.query.trim()) {
        emptyTitle.textContent = "Nothing matches.";
        emptyText.textContent = "Try a different word or clear the search to see the whole board.";
      } else if (state.filter === "done") {
        emptyTitle.textContent = "Nothing done yet.";
        emptyText.textContent = "Finish a task and it will wait for you here.";
      } else if (state.filter === "active") {
        emptyTitle.textContent = "All clear.";
        emptyText.textContent = "Every task is done. Add a new small step when you are ready.";
      } else {
        emptyTitle.textContent = "A clear board.";
        emptyText.textContent = "Add your first task on the left and it will show up here.";
      }

      updateSummary();
      return;
    }

    emptyState.hidden = true;
    visible.forEach((task) => list.append(makeTaskElement(task)));

    const visibleWord = visible.length === 1 ? "task" : "tasks";
    listFootnote.textContent = `Showing ${visible.length} ${visibleWord} of ${state.tasks.length} · saved on the server`;
    updateSummary();
  };

  const loadBoard = async () => {
    state.loading = true;
    state.error = "";
    syncAddButton();
    render();

    try {
      const response = await fetch(`${API_URL}?t=${Date.now()}`, {
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      let data;

      try {
        data = await response.json();
      } catch {
        throw new Error("The server returned an unreadable response.");
      }

      if (!response.ok || !data.ok) {
        throw new Error(data.error || "The task board could not be loaded.");
      }

      applyBoard(data);
      state.csrfToken = typeof data.csrf_token === "string" ? data.csrf_token : "";

      if (!state.csrfToken) {
        throw new Error("The security token was missing. Refresh the page.");
      }
    } catch (error) {
      state.error = error instanceof Error
        ? error.message
        : "The task board could not be loaded.";
    } finally {
      state.loading = false;
      syncAddButton();
      render();
    }
  };

  const handleAdd = async (event) => {
    event.preventDefault();

    const title = titleInput.value.trim();

    if (!title) {
      showToast("Give the task a title first.", "error");
      titleInput.focus();
      return;
    }

    setAddBusy(true);

    try {
      const data = await request({
        action: "add",
        title,
        priority: priorityInput?.value || "medium",
        due: dueInput?.value || "",
        csrf: state.csrfToken,
      });

      applyBoard(data);
      titleInput.value = "";
      if (dueInput) {
        dueInput.value = "";
      }

      render();
      showToast(data.message || "Task added.");
      titleInput.focus();
    } catch (error) {
      showToast(error instanceof Error ? error.message : "The task could not be added.", "error");
    } finally {
      setAddBusy(false);
    }
  };

  const handleTaskAction = async (event) => {
    const button = event.target.closest("button[data-action]");

    if (!button) {
      return;
    }

    const item = button.closest(".task");
    const id = item?.dataset.taskId;
    const action = button.dataset.action;

    if (!item || !id || state.busy) {
      return;
    }

    item.classList.add("is-busy");

    try {
      const data = await request({
        action,
        id,
        csrf: state.csrfToken,
      });

      applyBoard(data);
      render();
      showToast(data.message || (action === "delete" ? "Task deleted." : "Task updated."));
    } catch (error) {
      item.classList.remove("is-busy");
      showToast(error instanceof Error ? error.message : "The task could not be updated.", "error");
    }
  };

  const handleClear = async () => {
    if (state.busy) {
      return;
    }

    state.busy = true;
    updateSummary();

    try {
      const data = await request({
        action: "clear_completed",
        csrf: state.csrfToken,
      });

      applyBoard(data);
      render();
      showToast(data.message || "Completed tasks cleared.");
    } catch (error) {
      showToast(error instanceof Error ? error.message : "Completed tasks could not be cleared.", "error");
    } finally {
      state.busy = false;
      updateSummary();
    }
  };

  form.addEventListener("submit", handleAdd);
  titleInput?.addEventListener("input", syncAddButton);

  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      state.filter = button.dataset.taskFilter || "all";

      filterButtons.forEach((filterButton) => {
        const isActive = filterButton === button;
        filterButton.classList.toggle("is-active", isActive);
        filterButton.setAttribute("aria-pressed", String(isActive));
      });

      render();
    });
  });

  searchInput?.addEventListener("input", () => {
    state.query = searchInput.value;
    render();
  });

  list.addEventListener("click", handleTaskAction);
  clearButton?.addEventListener("click", handleClear);

  loadBoard();
})();
