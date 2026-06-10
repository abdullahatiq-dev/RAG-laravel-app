# Laravel RAG Chat Application

A Retrieval-Augmented Generation (RAG) chatbot built with Laravel and FastAPI. The application allows users to upload documents, automatically extract and chunk their content, generate embeddings using a Python-based embedding service, and retrieve relevant information to provide context-aware answers using an LLM.

## Features

- 📄 PDF document ingestion and text extraction
- ✂️ Automatic document chunking
- 🧠 Embedding generation using Sentence Transformers
- 🔍 Semantic search with cosine similarity
- 🤖 LLM-powered question answering
- 🔗 Retrieval-Augmented Generation (RAG) pipeline
- ⚡ Laravel backend with FastAPI embedding service
- 📝 Support for dynamic knowledge bases through uploaded documents

## Tech Stack

- **Laravel 12**
- **FastAPI**
- **Sentence Transformers (all-MiniLM-L6-v2)**
- **MySQL**
- **Groq API / OpenAI-compatible LLMs**
- **PHP**
- **Python**

## Architecture

```text
PDF Upload
     ↓
Text Extraction
     ↓
Chunking
     ↓
Embedding Generation
     ↓
Store Chunks + Embeddings
     ↓
User Question
     ↓
Query Embedding
     ↓
Semantic Search
     ↓
Retrieve Relevant Chunks
     ↓
LLM
     ↓
Context-Aware Response
```

This project demonstrates how to build a complete RAG system from scratch without relying on frameworks like LangChain or LlamaIndex, providing a deeper understanding of embeddings, vector search, and AI-powered document retrieval.
