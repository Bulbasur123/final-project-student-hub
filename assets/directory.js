/* ==========================================================================
   Student Hub — XML directory
   Loads data/student_data.xml with XMLHttpRequest and renders the records.
   ========================================================================== */

(() => {
  "use strict";

  const list = document.getElementById("directory-list");
  const status = document.getElementById("directory-status");
  const count = document.getElementById("directory-count");
  const search = document.getElementById("directory-search");

  if (!list || !status || !count) {
    return;
  }

  const state = {
    students: [],
    query: "",
    loading: true,
    error: "",
  };

  const setStatus = (message, variant = "") => {
    const dot = document.createElement("span");
    dot.className = "status-dot";
    status.replaceChildren(dot, document.createTextNode(` ${message}`));
    status.classList.toggle("is-error", variant === "error");
    status.classList.toggle("is-success", variant === "success");
  };

  const getInitials = (name) => {
    const parts = String(name)
      .trim()
      .split(/\s+/)
      .filter(Boolean);

    if (parts.length === 0) {
      return "??";
    }

    return parts
      .slice(0, 2)
      .map((part) => part.charAt(0).toUpperCase())
      .join("");
  };

  const getVisibleStudents = () => {
    const query = state.query.trim().toLowerCase();

    if (!query) {
      return state.students;
    }

    return state.students.filter((student) => {
      return [student.id, student.name, student.course, student.year]
        .join(" ")
        .toLowerCase()
        .includes(query);
    });
  };

  const makeStudentCard = (student) => {
    const card = document.createElement("article");
    card.className = "student-card";

    const top = document.createElement("div");
    top.className = "student-top";

    const avatar = document.createElement("span");
    avatar.className = "student-avatar";
    avatar.setAttribute("aria-hidden", "true");
    avatar.textContent = getInitials(student.name);

    const id = document.createElement("span");
    id.className = "student-id";
    id.textContent = `ID ${student.id}`;

    top.append(avatar, id);

    const name = document.createElement("h3");
    name.textContent = student.name;

    const meta = document.createElement("div");
    meta.className = "student-meta";

    const course = document.createElement("span");
    course.className = "student-course";
    course.textContent = student.course;

    const year = document.createElement("span");
    year.className = "student-year";
    year.textContent = `Year ${student.year}`;

    meta.append(course, year);
    card.append(top, name, meta);

    return card;
  };

  const renderEmpty = (title, copy) => {
    const empty = document.createElement("div");
    empty.className = "directory-empty";

    const heading = document.createElement("strong");
    heading.textContent = title;

    const text = document.createElement("span");
    text.textContent = copy;

    empty.append(heading, text);
    list.append(empty);
  };

  const render = () => {
    const visible = getVisibleStudents();

    list.replaceChildren();

    if (state.loading) {
      list.setAttribute("aria-busy", "true");
      count.textContent = "Loading…";
      return;
    }

    list.setAttribute("aria-busy", "false");

    if (state.error) {
      count.textContent = "0 records";
      renderEmpty("The directory could not be loaded.", state.error);
      return;
    }

    if (visible.length === 0) {
      count.textContent = state.students.length === 0
        ? "0 records"
        : `0 of ${state.students.length} records`;
      renderEmpty(
        "No matches found.",
        "Try another name, course, or student ID."
      );
      return;
    }

    visible.forEach((student) => list.append(makeStudentCard(student)));

    count.textContent = visible.length === state.students.length
      ? `${state.students.length} record${state.students.length === 1 ? "" : "s"}`
      : `${visible.length} of ${state.students.length} records`;
  };

  const parseStudents = (xmlText) => {
    const parser = new DOMParser();
    const documentNode = parser.parseFromString(xmlText, "application/xml");

    if (documentNode.querySelector("parsererror")) {
      throw new Error("The XML file could not be parsed.");
    }

    return [...documentNode.querySelectorAll("student")]
      .map((node) => {
        const read = (tag) => node.querySelector(tag)?.textContent.trim() || "";

        return {
          id: read("id"),
          name: read("name"),
          course: read("course"),
          year: read("year"),
        };
      })
      .filter((student) => student.name !== "");
  };

  const loadDirectory = () => {
    state.loading = true;
    state.error = "";
    setStatus("Reading XML…");
    render();

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "data/student_data.xml", true);
    xhr.timeout = 12000;
    xhr.setRequestHeader("Accept", "application/xml");

    xhr.onreadystatechange = () => {
      if (xhr.readyState !== 4) {
        return;
      }

      if (xhr.status < 200 || xhr.status >= 300) {
        state.loading = false;
        state.error = xhr.status === 0
          ? "The XML request could not reach the server. Open the site through PHP and try again."
          : `The XML request returned HTTP ${xhr.status}.`;
        setStatus("XML request failed", "error");
        render();
        return;
      }

      try {
        state.students = parseStudents(xhr.responseText);
        state.loading = false;
        setStatus(`XML loaded · HTTP ${xhr.status}`, "success");
        render();
      } catch (error) {
        state.loading = false;
        state.error = error instanceof Error
          ? error.message
          : "The XML data could not be read.";
        setStatus("XML parse failed", "error");
        render();
      }
    };

    xhr.onerror = () => {
      state.loading = false;
      state.error = "The XML request could not reach the server.";
      setStatus("XML request failed", "error");
      render();
    };

    xhr.ontimeout = () => {
      state.loading = false;
      state.error = "The XML request timed out.";
      setStatus("XML request timed out", "error");
      render();
    };

    xhr.send();
  };

  search?.addEventListener("input", () => {
    state.query = search.value;
    render();
  });

  loadDirectory();
})();
