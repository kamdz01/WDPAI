--
-- PostgreSQL database cluster dump
--

-- Started on 2024-01-17 16:27:38 UTC

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Roles
--

CREATE ROLE docker;
ALTER ROLE docker WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN REPLICATION BYPASSRLS PASSWORD 'SCRAM-SHA-256$4096:6CZz3kDfU9wL4QNPDewNSw==$bOc02dFEi/7fcHwoGRIB9qxKz0b7QWjQoqMheU9lx+Y=:/UyWa8vZP7Z0pqIt1M3jXtymDeG+PxOJbDokQQp28H0=';

--
-- User Configurations
--








--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1 (Debian 16.1-1.pgdg120+1)
-- Dumped by pg_dump version 16.1

-- Started on 2024-01-17 16:27:38 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Completed on 2024-01-17 16:27:38 UTC

--
-- PostgreSQL database dump complete
--

--
-- Database "db" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1 (Debian 16.1-1.pgdg120+1)
-- Dumped by pg_dump version 16.1

-- Started on 2024-01-17 16:27:38 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3407 (class 1262 OID 16384)
-- Name: db; Type: DATABASE; Schema: -; Owner: docker
--

CREATE DATABASE db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE db OWNER TO docker;

\connect db

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 229 (class 1255 OID 16497)
-- Name: get_user_notes(integer); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.get_user_notes(userid integer) RETURNS TABLE(user_id bigint, login text, note_id integer, note_content text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY SELECT unv.user_id, unv.login, unv.note_id, unv.note_content
                 FROM user_notes_view unv
                 WHERE unv.user_id = userid;
END;
$$;


ALTER FUNCTION public.get_user_notes(userid integer) OWNER TO docker;

--
-- TOC entry 225 (class 1255 OID 16399)
-- Name: set_add_date_time(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.set_add_date_time() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Set add_date to the current date and time
    NEW.add_date := CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.set_add_date_time() OWNER TO docker;

--
-- TOC entry 226 (class 1255 OID 16485)
-- Name: set_default_role(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.set_default_role() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.role_id := 2;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.set_default_role() OWNER TO docker;

--
-- TOC entry 227 (class 1255 OID 16481)
-- Name: set_note_creation_date(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.set_note_creation_date() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.creation_date := CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.set_note_creation_date() OWNER TO docker;

--
-- TOC entry 228 (class 1255 OID 16483)
-- Name: update_last_modified(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.update_last_modified() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.last_modified := CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_last_modified() OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 220 (class 1259 OID 16439)
-- Name: note_roles; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.note_roles (
    note_role_id integer NOT NULL,
    note_role_name character varying(50) NOT NULL
);


ALTER TABLE public.note_roles OWNER TO docker;

--
-- TOC entry 219 (class 1259 OID 16438)
-- Name: note_roles_note_role_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.note_roles_note_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.note_roles_note_role_id_seq OWNER TO docker;

--
-- TOC entry 3408 (class 0 OID 0)
-- Dependencies: 219
-- Name: note_roles_note_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.note_roles_note_role_id_seq OWNED BY public.note_roles.note_role_id;


--
-- TOC entry 218 (class 1259 OID 16408)
-- Name: notes; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.notes (
    note_id integer NOT NULL,
    note_title character varying(100) NOT NULL,
    note_content text NOT NULL,
    creation_date timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    last_modified timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.notes OWNER TO docker;

--
-- TOC entry 217 (class 1259 OID 16407)
-- Name: notes_note_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.notes_note_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notes_note_id_seq OWNER TO docker;

--
-- TOC entry 3409 (class 0 OID 0)
-- Dependencies: 217
-- Name: notes_note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.notes_note_id_seq OWNED BY public.notes.note_id;


--
-- TOC entry 222 (class 1259 OID 16448)
-- Name: roles; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.roles (
    role_id integer NOT NULL,
    role_name character varying(50) NOT NULL
);


ALTER TABLE public.roles OWNER TO docker;

--
-- TOC entry 221 (class 1259 OID 16447)
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.roles_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_role_id_seq OWNER TO docker;

--
-- TOC entry 3410 (class 0 OID 0)
-- Dependencies: 221
-- Name: roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.roles_role_id_seq OWNED BY public.roles.role_id;


--
-- TOC entry 223 (class 1259 OID 16461)
-- Name: user_notes; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_notes (
    user_id integer NOT NULL,
    note_id integer NOT NULL,
    note_role_id integer
);


ALTER TABLE public.user_notes OWNER TO docker;

--
-- TOC entry 216 (class 1259 OID 16390)
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    user_id bigint NOT NULL,
    login text NOT NULL,
    email text NOT NULL,
    password text NOT NULL,
    add_date timestamp with time zone,
    role_id bigint
);


ALTER TABLE public.users OWNER TO docker;

--
-- TOC entry 224 (class 1259 OID 16487)
-- Name: user_notes_view; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.user_notes_view AS
 SELECT u.user_id,
    u.login,
    n.note_id,
    n.note_content
   FROM ((public.users u
     JOIN public.user_notes un ON ((u.user_id = un.user_id)))
     JOIN public.notes n ON ((un.note_id = n.note_id)))
  ORDER BY u.user_id;


ALTER VIEW public.user_notes_view OWNER TO docker;

--
-- TOC entry 215 (class 1259 OID 16389)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

ALTER TABLE public.users ALTER COLUMN user_id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 3234 (class 2604 OID 16442)
-- Name: note_roles note_role_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.note_roles ALTER COLUMN note_role_id SET DEFAULT nextval('public.note_roles_note_role_id_seq'::regclass);


--
-- TOC entry 3231 (class 2604 OID 16411)
-- Name: notes note_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.notes ALTER COLUMN note_id SET DEFAULT nextval('public.notes_note_id_seq'::regclass);


--
-- TOC entry 3235 (class 2604 OID 16451)
-- Name: roles role_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles ALTER COLUMN role_id SET DEFAULT nextval('public.roles_role_id_seq'::regclass);


--
-- TOC entry 3241 (class 2606 OID 16446)
-- Name: note_roles note_roles_note_role_name_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.note_roles
    ADD CONSTRAINT note_roles_note_role_name_key UNIQUE (note_role_name);


--
-- TOC entry 3243 (class 2606 OID 16444)
-- Name: note_roles note_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.note_roles
    ADD CONSTRAINT note_roles_pkey PRIMARY KEY (note_role_id);


--
-- TOC entry 3239 (class 2606 OID 16417)
-- Name: notes notes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_pkey PRIMARY KEY (note_id);


--
-- TOC entry 3245 (class 2606 OID 16453)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- TOC entry 3247 (class 2606 OID 16455)
-- Name: roles roles_role_name_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_role_name_key UNIQUE (role_name);


--
-- TOC entry 3249 (class 2606 OID 16465)
-- Name: user_notes user_notes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_notes
    ADD CONSTRAINT user_notes_pkey PRIMARY KEY (user_id, note_id);


--
-- TOC entry 3237 (class 2606 OID 16396)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 3254 (class 2620 OID 16400)
-- Name: users add_date_time_trigger; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER add_date_time_trigger BEFORE INSERT ON public.users FOR EACH ROW EXECUTE FUNCTION public.set_add_date_time();


--
-- TOC entry 3256 (class 2620 OID 16484)
-- Name: notes last_modified_trigger; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER last_modified_trigger BEFORE UPDATE ON public.notes FOR EACH ROW EXECUTE FUNCTION public.update_last_modified();


--
-- TOC entry 3257 (class 2620 OID 16482)
-- Name: notes note_creation_date_trigger; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER note_creation_date_trigger BEFORE INSERT ON public.notes FOR EACH ROW EXECUTE FUNCTION public.set_note_creation_date();


--
-- TOC entry 3255 (class 2620 OID 16486)
-- Name: users trigger_set_default_role; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER trigger_set_default_role BEFORE INSERT ON public.users FOR EACH ROW EXECUTE FUNCTION public.set_default_role();


--
-- TOC entry 3251 (class 2606 OID 16471)
-- Name: user_notes user_notes_note_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_notes
    ADD CONSTRAINT user_notes_note_id_fkey FOREIGN KEY (note_id) REFERENCES public.notes(note_id);


--
-- TOC entry 3252 (class 2606 OID 16476)
-- Name: user_notes user_notes_note_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_notes
    ADD CONSTRAINT user_notes_note_role_id_fkey FOREIGN KEY (note_role_id) REFERENCES public.note_roles(note_role_id);


--
-- TOC entry 3253 (class 2606 OID 16466)
-- Name: user_notes user_notes_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_notes
    ADD CONSTRAINT user_notes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 3250 (class 2606 OID 16456)
-- Name: users users_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(role_id);


-- Completed on 2024-01-17 16:27:38 UTC

--
-- PostgreSQL database dump complete
--

--
-- Database "postgres" dump
--

\connect postgres

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1 (Debian 16.1-1.pgdg120+1)
-- Dumped by pg_dump version 16.1

-- Started on 2024-01-17 16:27:38 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Completed on 2024-01-17 16:27:38 UTC

--
-- PostgreSQL database dump complete
--

-- Completed on 2024-01-17 16:27:38 UTC

--
-- PostgreSQL database cluster dump complete
--

